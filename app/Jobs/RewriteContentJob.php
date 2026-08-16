<?php

namespace App\Jobs;

use App\DTOs\AI\GenerateRequest;
use App\Enums\AIModelStatus;
use App\Enums\JobStatus;
use App\Models\AiModel;
use App\Models\PromptVersion;
use App\Models\RewriteJob;
use App\Services\AI\AIManager;
use App\Services\Content\HtmlParserService;
use App\Services\Content\ProtectedValueService;
use App\Services\Rewrite\DiffService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class RewriteContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public RewriteJob $rewriteJob
    ) {}

    public function handle(
        AIManager $aiManager,
        HtmlParserService $htmlParserService,
        ProtectedValueService $protectedValueService,
        DiffService $diffService
    ): void {
        Log::info("Starting RewriteContentJob for job #{$this->rewriteJob->id}");

        $this->rewriteJob->status = JobStatus::AiProcessing;
        $this->rewriteJob->save();

        $this->rewriteJob->loadMissing(['website', 'page', 'aiModel.provider', 'promptVersion.template', 'result']);
        $result  = $this->rewriteJob->result;
        $website = $this->rewriteJob->website;
        $page    = $this->rewriteJob->page;

        if (! $result || empty($result->original_segments)) {
            throw new RuntimeException("No original segments found for job #{$this->rewriteJob->id}.");
        }

        // Resolve AI Model
        $aiModel = $this->rewriteJob->aiModel
            ?? $page?->aiModel
            ?? $website?->defaultAiModel
            ?? AiModel::where('status', AIModelStatus::Active)->first();

        if (! $aiModel) {
            throw new RuntimeException("No active AI model configured for job #{$this->rewriteJob->id}.");
        }

        // Resolve Prompt Version
        $promptVersion = $this->rewriteJob->promptVersion
            ?? $page?->promptVersion
            ?? $website?->defaultPromptVersion
            ?? PromptVersion::where('is_current', true)->first();

        if (! $promptVersion) {
            throw new RuntimeException("No active prompt version configured for job #{$this->rewriteJob->id}.");
        }

        // Persist model & prompt on job if not set
        $this->rewriteJob->ai_model_id       ??= $aiModel->id;
        $this->rewriteJob->prompt_version_id ??= $promptVersion->id;

        // Extract protected values for prompt injection
        $customTerms        = array_merge($website->protected_terms ?? [], $page->protected_values ?? []);
        $extractedProtected = $protectedValueService->extractFromSegments($result->original_segments);
        $protectedValues    = $protectedValueService->mergeWithCustomTerms($extractedProtected, $customTerms);

        // Build generate request
        $generateRequest = new GenerateRequest(
            systemPrompt:     $promptVersion->system_prompt ?? '',
            instructions:     $promptVersion->instructions ?? '',
            segments:         $result->original_segments,
            modelId:          $aiModel->model_id,
            temperature:      (float) ($aiModel->temperature ?? 0.7),
            maxOutputTokens:  $aiModel->max_output_tokens,
            contextLength:    $aiModel->context_length,
            structuredOutput: true,
            protectedValues:  $protectedValues
        );

        $driver = $aiModel->provider?->driver?->value ?? config('ai.default_provider', 'ollama');

        Log::info("Sending AI generation request for job #{$this->rewriteJob->id} using model [{$aiModel->model_id}] and driver [{$driver}]");

        $response = $aiManager->generate($generateRequest, $driver);

        if (! $response->success) {
            throw new RuntimeException("AI Content Generation failed: " . ($response->error ?? 'Unknown error'));
        }

        // Reconstruct HTML with rewritten segments
        $targetFilePath = rtrim($this->rewriteJob->workspace_path, '/\\') . DIRECTORY_SEPARATOR . ltrim($page->path, '/\\');

        if (! file_exists($targetFilePath)) {
            throw new RuntimeException("Target HTML file not found at [{$targetFilePath}] during reconstruction.");
        }

        $originalHtml  = file_get_contents($targetFilePath);
        $rewrittenHtml = $htmlParserService->reconstruct($originalHtml, $result->original_segments, $response->segments);

        // Write rewritten HTML back to workspace
        file_put_contents($targetFilePath, $rewrittenHtml);

        // Compute diff
        $diffData = $diffService->generateSegmentDiff($result->original_segments, $response->segments);

        // Calculate new word count
        $newWordCount = array_sum(array_map(
            fn($s) => str_word_count(strip_tags($s['rewritten'] ?? '')),
            $response->segments
        ));

        $rewrittenHash = hash('sha256', implode('||', array_column($response->segments, 'rewritten')));

        // Update RewriteResult
        $result->rewritten_segments  = $response->segments;
        $result->diff_data           = json_encode($diffData);
        $result->rewritten_html_hash = hash('sha256', $rewrittenHtml);
        $result->ai_request_tokens   = $response->requestTokens;
        $result->ai_response_tokens  = $response->responseTokens;
        $result->ai_duration_ms      = $response->durationMs;
        $result->save();

        // Update RewriteJob
        $this->rewriteJob->rewritten_content_hash = $rewrittenHash;
        $this->rewriteJob->new_word_count         = $newWordCount;
        $this->rewriteJob->save();

        Log::info("RewriteContentJob completed for job #{$this->rewriteJob->id}. New word count: {$newWordCount}");
    }
}
