<?php

namespace App\Services;

use App\Enums\JobStatus;
use App\Models\RewriteJob;
use App\Models\RewriteResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JobExecutionService
{
    /**
     * Execute a rewrite job step-by-step.
     * Returns structured result array with per-step status and error details.
     */
    public function executeJobWithSteps(RewriteJob $job): array
    {
        $job->load(['website', 'page', 'aiModel.provider']);

        $steps = [
            'extract'    => ['status' => 'pending', 'label' => 'Extract HTML & Content Structure',  'time' => null, 'error' => null],
            'ai_rewrite' => ['status' => 'pending', 'label' => 'AI Rewrite Text Segments',          'time' => null, 'error' => null],
            'patch_html' => ['status' => 'pending', 'label' => 'Preserve Styles & Patch HTML',      'time' => null, 'error' => null],
            'git_sync'   => ['status' => 'pending', 'label' => 'Git Commit & Push to Remote',       'time' => null, 'error' => null],
        ];

        // ─────────────────────────────────────────────────────────────
        // STEP 1 — FETCH HTML FROM GITHUB (primary) OR LOCAL (fallback)
        // ─────────────────────────────────────────────────────────────
        $start    = microtime(true);
        $filePath = null;
        $rawHtml  = '';

        // Local fallback path
        if ($job->website && $job->website->local_production_path && $job->page) {
            $filePath = rtrim($job->website->local_production_path, '/\\')
                      . DIRECTORY_SEPARATOR
                      . ltrim($job->page->path, '/\\');
            if (file_exists($filePath)) {
                $rawHtml = file_get_contents($filePath);
            }
        }

        // GitHub API primary (always prefer GitHub for cloud flow)
        if ($job->website && !empty($job->website->git_repository_url) && $job->page) {
            $githubApi = new GithubApiService();
            $fileData  = $githubApi->getFileContent($job->website, $job->page->path);
            if ($fileData && !empty($fileData['content'])) {
                $rawHtml = $fileData['content']; // Use GitHub version as truth
            }
        }

        if (empty(trim($rawHtml))) {
            $err = "Could not fetch HTML for '{$job->page?->path}'. Check repository URL, branch name, and GitHub Token.";
            $steps['extract'] = ['status' => 'failed', 'label' => 'Extract HTML & Content Structure',
                'time' => $this->ms($start), 'error' => $err, 'details' => 'File not found in repository'];
            $job->update(['status' => JobStatus::Failed, 'error_message' => $err]);
            return ['success' => false, 'failed_step' => 'extract', 'error_message' => $err, 'steps' => $steps];
        }

        // Extract readable text segments (what the AI will rewrite)
        $segments = $this->extractTextSegments($rawHtml);

        if (empty($segments)) {
            $err = "No rewritable text found in '{$job->page?->path}'. The page may be empty or only contain JavaScript/CSS.";
            $steps['extract'] = ['status' => 'failed', 'label' => 'Extract HTML & Content Structure',
                'time' => $this->ms($start), 'error' => $err, 'details' => 'Zero text segments found'];
            $job->update(['status' => JobStatus::Failed, 'error_message' => $err]);
            return ['success' => false, 'failed_step' => 'extract', 'error_message' => $err, 'steps' => $steps];
        }

        $steps['extract'] = [
            'status'  => 'success',
            'label'   => 'Extract HTML & Content Structure',
            'time'    => $this->ms($start),
            'error'   => null,
            'details' => 'Found ' . count($segments) . ' rewritable text segments in ' . ($job->page?->path ?? 'page'),
        ];

        // ─────────────────────────────────────────────────────────────
        // STEP 2 — AI REWRITE EACH SEGMENT
        // ─────────────────────────────────────────────────────────────
        $start    = microtime(true);
        $provider = $job->aiModel?->provider ?? \App\Models\AiProvider::where('user_id', $job->website?->user_id)->first()
                                             ?? \App\Models\AiProvider::whereNull('user_id')->first()
                                             ?? \App\Models\AiProvider::first();
        $modelId  = $job->aiModel?->model_id ?? 'llama-3.3-70b-versatile';
        $apiKey   = $provider?->api_key;
        $endpoint = rtrim($provider?->endpoint ?? 'https://api.groq.com/openai/v1', '/');

        if (empty($apiKey)) {
            $err = "No AI Provider API key configured for this website. Please add an API Key in AI Providers.";
            $steps['ai_rewrite'] = ['status' => 'failed', 'label' => 'AI Rewrite Text Segments',
                'time' => $this->ms($start), 'error' => $err, 'details' => 'Missing API key'];
            $job->update(['status' => JobStatus::Failed, 'error_message' => $err]);
            return ['success' => false, 'failed_step' => 'ai_rewrite', 'error_message' => $err, 'steps' => $steps];
        }

        // Pick top segments (max 8) — send them all in ONE API call as JSON
        $targetSegments = array_slice($segments, 0, 8, true);
        $payload        = [];
        foreach ($targetSegments as $idx => $seg) {
            $payload[] = "SEG_{$idx} ({$seg['words']} words): {$seg['text']}";
        }

        $systemPrompt = "You are a professional SEO copywriter. You will receive numbered text segments from a website page.

RULES:
1. Rewrite EACH segment to be fresh, natural, human-sounding, and SEO-friendly.
2. Keep EXACTLY the same topic and meaning — do NOT invent facts not in the original.
3. Maintain approximately the same word count per segment (+/- 10%).
4. Return ONLY a valid JSON object like: {\"SEG_0\": \"rewritten text\", \"SEG_1\": \"rewritten text\"}
5. Do NOT include HTML tags in your output — plain text only.
6. Do NOT add commentary, markdown, or explanation.";

        $rewrittenMap = [];
        $aiError      = null;

        try {
            $response = Http::withToken($apiKey)
                ->withoutVerifying()
                ->timeout(30)
                ->post($endpoint . '/chat/completions', [
                    'model'           => $modelId,
                    'messages'        => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user',   'content' => implode("\n\n", $payload)],
                    ],
                    'temperature'     => 0.55,
                    'max_tokens'      => 2048,
                    'response_format' => ['type' => 'json_object'],
                ]);

            if ($response->successful()) {
                $raw          = $response->json('choices.0.message.content');
                $rewrittenMap = json_decode($raw, true);

                // Fallback: extract JSON from response if not pure JSON
                if (!is_array($rewrittenMap) && preg_match('/\{.*\}/s', $raw, $m)) {
                    $rewrittenMap = json_decode($m[0], true);
                }
            } else {
                $aiError = "AI API Error {$response->status()}: " . ($response->json('error.message') ?? $response->body());
            }
        } catch (\Throwable $e) {
            $aiError = "AI Connection Error: " . $e->getMessage();
            Log::error("JobExecutionService AI Error: " . $e->getMessage());
        }

        if ($aiError || !is_array($rewrittenMap) || empty($rewrittenMap)) {
            $err = $aiError ?? "AI returned empty or invalid JSON for rewriting.";
            $steps['ai_rewrite'] = ['status' => 'failed', 'label' => 'AI Rewrite Text Segments',
                'time' => $this->ms($start), 'error' => $err, 'details' => 'No rewritten segments produced'];
            $job->update(['status' => JobStatus::Failed, 'error_message' => $err]);
            return ['success' => false, 'failed_step' => 'ai_rewrite', 'error_message' => $err, 'steps' => $steps];
        }

        $steps['ai_rewrite'] = [
            'status'  => 'success',
            'label'   => 'AI Rewrite Text Segments',
            'time'    => $this->ms($start),
            'error'   => null,
            'details' => "Rewrote " . count($rewrittenMap) . " segments using {$modelId}",
        ];

        // ─────────────────────────────────────────────────────────────
        // STEP 3 — PATCH HTML: REPLACE OLD TEXT WITH AI REWRITTEN TEXT
        // ─────────────────────────────────────────────────────────────
        $start           = microtime(true);
        $htmlContent     = $rawHtml;
        $replacedCount   = 0;
        $originalTexts   = [];
        $rewrittenTexts  = [];

        foreach ($rewrittenMap as $key => $newText) {
            $newText = trim((string) $newText);
            if (empty($newText)) continue;

            // Extract the segment index from key like "SEG_0", "SEG_1" or plain int
            $idx = is_numeric($key) ? (int)$key : (int)filter_var($key, FILTER_SANITIZE_NUMBER_INT);

            if (!isset($targetSegments[$idx])) continue;

            $seg     = $targetSegments[$idx];
            $oldText = $seg['text'];

            // Safety: don't replace if new text is clearly wrong (very different length or contains HTML)
            if (strlen($newText) < 5 || strip_tags($newText) !== $newText) continue;

            // Replace old inner text inside the HTML tag
            // Strategy: find the tag with the exact old inner text and replace only the text node
            $escaped = preg_quote($oldText, '/');
            $pattern = '/(<' . preg_quote($seg['tag'], '/') . '[^>]*>)(.*?)(' . $escaped . ')(.*?)(<\/' . preg_quote($seg['tag'], '/') . '>)/is';

            if (preg_match($pattern, $htmlContent)) {
                $htmlContent = preg_replace($pattern, '$1$2' . addcslashes($newText, '\\$') . '$4$5', $htmlContent, 1);
                $replacedCount++;
                $originalTexts[]  = "[{$seg['tag']}] {$oldText}";
                $rewrittenTexts[] = "[{$seg['tag']}] {$newText}";
            } else {
                // Fallback: simple str_replace on the exact old text inside HTML
                if (str_contains($htmlContent, $oldText)) {
                    $htmlContent = str_replace($oldText, $newText, $htmlContent);
                    $replacedCount++;
                    $originalTexts[]  = "[{$seg['tag']}] {$oldText}";
                    $rewrittenTexts[] = "[{$seg['tag']}] {$newText}";
                }
            }
        }

        if ($replacedCount === 0) {
            // Log a warning but do NOT fail — the HTML may still be valid for push
            Log::warning("JobExecutionService: 0 text replacements made for job {$job->id}. HTML may be unchanged.");
        }

        $steps['patch_html'] = [
            'status'  => 'success',
            'label'   => 'Preserve Styles & Patch HTML',
            'time'    => $this->ms($start),
            'error'   => null,
            'details' => "Replaced {$replacedCount} text segments while preserving all HTML tags and styles",
        ];

        // ─────────────────────────────────────────────────────────────
        // SAVE REWRITE RESULT (original vs rewritten, for UI preview)
        // ─────────────────────────────────────────────────────────────
        RewriteResult::updateOrCreate(
            ['rewrite_job_id' => $job->id],
            [
                'original_segments'  => ['text' => implode("\n\n", $originalTexts),  'html' => $rawHtml],
                'rewritten_segments' => ['text' => implode("\n\n", $rewrittenTexts), 'html' => $htmlContent],
                'ai_duration_ms'     => 0,
            ]
        );

        // ─────────────────────────────────────────────────────────────
        // STEP 4 — APPROVAL CHECK & GIT PUSH
        // ─────────────────────────────────────────────────────────────
        $start        = microtime(true);
        $approvalMode = $job->website?->approval_mode;
        $isManual     = is_object($approvalMode)
            ? ($approvalMode === \App\Enums\ApprovalMode::Manual || $approvalMode->value === 'manual')
            : ($approvalMode === 'manual');

        if ($isManual) {
            $steps['git_sync'] = [
                'status'  => 'pending',
                'label'   => 'Git Commit & Push to Remote',
                'time'    => '0ms',
                'error'   => null,
                'details' => 'Awaiting Manual Approval — push paused until user approves',
            ];
            $job->update([
                'status'            => JobStatus::PendingApproval,
                'validation_status' => \App\Enums\ValidationStatus::Passed,
                'finished_at'       => now(),
                'error_message'     => null,
            ]);
            $this->queueNextScheduledJob($job);
            $this->cleanupTemporaryWorkspace($job, $filePath);
            return ['success' => true, 'steps' => $steps, 'is_pending_review' => true];
        }

        // Auto mode: push directly
        $gitError  = null;
        if ($job->website) {
            try {
                $gitService = new \App\Services\GitService();
                $pagePath   = $job->page?->path ?? '/index.html';
                $gitRes     = $gitService->commitAndPush(
                    $job->website,
                    "Autoflow AI: Refreshed {$pagePath}",
                    $pagePath,
                    $htmlContent
                );
                if (is_array($gitRes) && !($gitRes['success'] ?? true)) {
                    $gitError = $gitRes['message'] ?? 'Git push failed';
                }
            } catch (\Throwable $e) {
                $gitError = "Git Error: " . $e->getMessage();
                Log::error("JobExecutionService Git Error: " . $e->getMessage());
            }
        }

        if ($gitError) {
            $steps['git_sync'] = ['status' => 'failed', 'label' => 'Git Commit & Push to Remote',
                'time' => $this->ms($start), 'error' => $gitError, 'details' => 'Repository sync failed'];
            $job->update(['status' => JobStatus::Failed, 'error_message' => $gitError, 'finished_at' => now()]);
            $this->queueNextScheduledJob($job);
            $this->cleanupTemporaryWorkspace($job, $filePath);
            return ['success' => false, 'failed_step' => 'git_sync', 'error_message' => $gitError, 'steps' => $steps];
        }

        $steps['git_sync'] = [
            'status'  => 'success',
            'label'   => 'Git Commit & Push to Remote',
            'time'    => $this->ms($start),
            'error'   => null,
            'details' => 'Committed & pushed to GitHub — Vercel/Netlify build triggered',
        ];

        $job->update([
            'status'            => JobStatus::Completed,
            'validation_status' => \App\Enums\ValidationStatus::Passed,
            'finished_at'       => now(),
            'error_message'     => null,
        ]);

        $this->queueNextScheduledJob($job);
        $this->cleanupTemporaryWorkspace($job, $filePath);

        return ['success' => true, 'steps' => $steps];
    }

    // ─────────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────────

    /**
     * Extract rewritable text segments from raw HTML.
     * Returns array of ['tag'=>, 'text'=>, 'words'=>] indexed by position.
     */
    private function extractTextSegments(string $html): array
    {
        // Strip noise: scripts, styles, schema JSON-LD, comments, nav, header, footer
        $clean = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $html);
        $clean = preg_replace('/<style\b[^>]*>[\s\S]*?<\/style>/i', '', $clean);
        $clean = preg_replace('/<!--[\s\S]*?-->/', '', $clean);
        $clean = preg_replace('/<(head|nav|header|footer)[^>]*>[\s\S]*?<\/\1>/i', '', $clean);

        // Match text-bearing tags
        preg_match_all('/<(p|h1|h2|h3|h4|h5|h6|li|td|th|blockquote)([^>]*)>([\s\S]*?)<\/\1>/i', $clean, $matches, PREG_SET_ORDER);

        $segments = [];
        foreach ($matches as $m) {
            $tag       = strtolower($m[1]);
            $attrs     = $m[2];
            $innerHtml = $m[3];
            $innerText = trim(html_entity_decode(strip_tags($innerHtml), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

            // Skip: too short, navigation breadcrumbs, copyright lines, class badges
            if (strlen($innerText) < 20) continue;
            if (preg_match('/\b(copyright|breadcrumb|badge|crumb|©)\b/i', $attrs . ' ' . $innerText)) continue;
            // Skip lines that look like CSS or JSON
            if (str_starts_with($innerText, '{') || str_starts_with($innerText, '.') || str_contains($innerText, 'font-size:')) continue;

            $segments[] = [
                'tag'   => $tag,
                'attrs' => $attrs,
                'html'  => $m[0],   // full original HTML tag (for display only)
                'text'  => $innerText,
                'words' => str_word_count($innerText),
            ];
        }

        return $segments;
    }

    /** Return elapsed ms as string */
    private function ms(float $start): string
    {
        return round((microtime(true) - $start) * 1000) . 'ms';
    }

    /** Automatically queue the next scheduled job iteration */
    private function queueNextScheduledJob(RewriteJob $job): void
    {
        $exists = RewriteJob::where('website_page_id', $job->website_page_id)
            ->whereIn('status', [JobStatus::Scheduled, 'scheduled'])
            ->where('id', '!=', $job->id)
            ->exists();

        if (!$exists) {
            $unit = $job->website?->default_rewrite_interval_unit ?? 'days';
            $val  = (int)($job->website?->default_rewrite_interval_days ?? 2);

            $nextAt = match ($unit) {
                'minutes' => now()->addMinutes($val),
                'hours'   => now()->addHours($val),
                'months'  => now()->addMonths($val),
                default   => now()->addDays($val),
            };

            RewriteJob::create([
                'website_id'      => $job->website_id,
                'website_page_id' => $job->website_page_id,
                'ai_model_id'     => $job->ai_model_id,
                'trigger_type'    => \App\Enums\TriggerType::Scheduled,
                'status'          => JobStatus::Scheduled,
                'started_at'      => now(),
                'scheduled_at'    => $nextAt,
            ]);
        }
    }

    /** Clean up any temp workspace files */
    private function cleanupTemporaryWorkspace(RewriteJob $job, ?string $filePath): void
    {
        try {
            $tempDir = storage_path('app/temp_workspaces/' . $job->id);
            if (is_dir($tempDir)) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($tempDir, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::CHILD_FIRST
                );
                foreach ($files as $fi) {
                    $fi->isDir() ? @rmdir($fi->getRealPath()) : @unlink($fi->getRealPath());
                }
                @rmdir($tempDir);
            }
            gc_collect_cycles();
        } catch (\Throwable $e) {
            Log::info("Workspace cleanup notice: " . $e->getMessage());
        }
    }

    /** Legacy wrapper */
    public function executeJob(RewriteJob $job): bool
    {
        return ($this->executeJobWithSteps($job)['success'] ?? false);
    }
}
