<?php

namespace App\Jobs;

use App\Enums\JobStatus;
use App\Models\RewriteJob;
use App\Models\RewriteResult;
use App\Services\Content\HtmlParserService;
use App\Services\Content\ProtectedValueService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ExtractContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public RewriteJob $rewriteJob
    ) {}

    public function handle(HtmlParserService $htmlParserService, ProtectedValueService $protectedValueService): void
    {
        Log::info("Starting ExtractContentJob for job #{$this->rewriteJob->id}");

        $this->rewriteJob->status = JobStatus::Extracting;
        $this->rewriteJob->save();

        $this->rewriteJob->loadMissing(['website', 'page']);
        $page    = $this->rewriteJob->page;
        $website = $this->rewriteJob->website;

        $workspacePath = $this->rewriteJob->workspace_path;

        if (! $workspacePath || ! is_dir($workspacePath)) {
            throw new RuntimeException("Workspace directory missing for job #{$this->rewriteJob->id}.");
        }

        $targetFilePath = rtrim($workspacePath, '/\\') . DIRECTORY_SEPARATOR . ltrim($page->path, '/\\');

        if (! file_exists($targetFilePath)) {
            throw new RuntimeException("Target file [{$page->path}] not found in workspace.");
        }

        $htmlContent = file_get_contents($targetFilePath);

        if ($htmlContent === false) {
            throw new RuntimeException("Failed to read HTML file content from [{$targetFilePath}].");
        }

        // Parse HTML and extract editable content segments
        $rewriteScope      = $page->rewrite_scope ?? [];
        $excludedSelectors = $page->excluded_selectors ?? [];
        $globalExclusions  = $website->global_exclusion_selectors ?? [];

        $extractionResult = $htmlParserService->extract(
            $htmlContent,
            $rewriteScope,
            $excludedSelectors,
            $globalExclusions
        );

        $segmentArrays = array_map(fn($s) => $s->toArray(), $extractionResult->segments);

        // Store or update RewriteResult
        RewriteResult::updateOrCreate(
            ['rewrite_job_id' => $this->rewriteJob->id],
            [
                'original_segments'  => $segmentArrays,
                'original_html_hash' => hash('sha256', $htmlContent),
            ]
        );

        // Update job content hash and word count
        $this->rewriteJob->original_content_hash = $extractionResult->contentHash;
        $this->rewriteJob->original_word_count   = $extractionResult->totalWordCount;
        $this->rewriteJob->save();

        Log::info("ExtractContentJob completed for job #{$this->rewriteJob->id}. Extracted " . count($segmentArrays) . " segments.");
    }
}
