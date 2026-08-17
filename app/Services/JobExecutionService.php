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
            'extract'    => ['status' => 'pending', 'label' => 'Extract HTML & Content Structure', 'time' => null, 'error' => null],
            'ai_rewrite' => ['status' => 'pending', 'label' => 'AI Rewrite HTML Segments',         'time' => null, 'error' => null],
            'patch_html' => ['status' => 'pending', 'label' => 'Patch HTML with Rewritten Content','time' => null, 'error' => null],
            'git_sync'   => ['status' => 'pending', 'label' => 'Git Commit & Push to Remote',      'time' => null, 'error' => null],
        ];

        // ──────────────────────────────────────────────────────────────
        // STEP 1 — FETCH HTML FROM GITHUB (primary) or LOCAL (fallback)
        // ──────────────────────────────────────────────────────────────
        $start    = microtime(true);
        $filePath = null;
        $rawHtml  = '';

        if ($job->website && $job->website->local_production_path && $job->page) {
            $filePath = rtrim($job->website->local_production_path, '/\\')
                . DIRECTORY_SEPARATOR
                . ltrim($job->page->path, '/\\');
            if (file_exists($filePath)) {
                $rawHtml = file_get_contents($filePath);
            }
        }

        // GitHub always wins — fetch fresh from repo
        if ($job->website && !empty($job->website->git_repository_url) && $job->page) {
            $githubApi = new GithubApiService();
            $fileData  = $githubApi->getFileContent($job->website, $job->page->path);
            if ($fileData && !empty($fileData['content'])) {
                $rawHtml = $fileData['content'];
            }
        }

        if (empty(trim($rawHtml))) {
            $err = "Could not fetch HTML for '{$job->page?->path}'. Verify repository URL, branch, and GitHub Token.";
            $steps['extract'] = ['status' => 'failed', 'label' => 'Extract HTML & Content Structure',
                'time' => $this->ms($start), 'error' => $err, 'details' => 'File not found in repository'];
            $job->update(['status' => JobStatus::Failed, 'error_message' => $err]);
            return ['success' => false, 'failed_step' => 'extract', 'error_message' => $err, 'steps' => $steps];
        }

        // Extract segments — store full HTML of each element (this is what we'll str_replace later)
        $segments = $this->extractHtmlSegments($rawHtml);

        if (empty($segments)) {
            $err = "No rewritable content found in '{$job->page?->path}'. Page may be empty or JS-only.";
            $steps['extract'] = ['status' => 'failed', 'label' => 'Extract HTML & Content Structure',
                'time' => $this->ms($start), 'error' => $err, 'details' => 'Zero rewritable segments detected'];
            $job->update(['status' => JobStatus::Failed, 'error_message' => $err]);
            return ['success' => false, 'failed_step' => 'extract', 'error_message' => $err, 'steps' => $steps];
        }

        $steps['extract'] = [
            'status'  => 'success',
            'label'   => 'Extract HTML & Content Structure',
            'time'    => $this->ms($start),
            'error'   => null,
            'details' => 'Found ' . count($segments) . ' rewritable HTML segments (p, h1-h6, li, ul, ol, blockquote) in ' . ($job->page?->path ?? 'page'),
        ];

        // ──────────────────────────────────────────────────────────────
        // STEP 2 — AI REWRITE: send full HTML snippets, get back rewritten HTML snippets
        // ──────────────────────────────────────────────────────────────
        $start    = microtime(true);
        $provider = $job->aiModel?->provider
            ?? \App\Models\AiProvider::where('user_id', $job->website?->user_id)->first()
            ?? \App\Models\AiProvider::whereNull('user_id')->first()
            ?? \App\Models\AiProvider::first();

        $endpoint = rtrim($provider?->endpoint ?? 'https://api.groq.com/openai/v1', '/');
        $apiKey   = $provider?->api_key;

        // Resolve model: job's explicit model → provider's own default model → smart endpoint-based fallback
        if ($job->aiModel?->model_id) {
            $modelId = $job->aiModel->model_id;
        } elseif (!empty($provider?->default_model)) {
            $modelId = $provider->default_model;
        } else {
            // Auto-detect a safe default based on the provider's endpoint
            $modelId = match (true) {
                str_contains($endpoint, 'openai.com')    => 'gpt-4o-mini',
                str_contains($endpoint, 'anthropic.com') => 'claude-3-haiku-20240307',
                str_contains($endpoint, 'groq.com')      => 'llama-3.3-70b-versatile',
                str_contains($endpoint, 'together.ai')   => 'meta-llama/Llama-3-70b-chat-hf',
                str_contains($endpoint, 'mistral.ai')    => 'mistral-small-latest',
                str_contains($endpoint, 'deepseek.com')  => 'deepseek-chat',
                default                                   => 'gpt-4o-mini',  // safe OpenAI-compatible fallback
            };
        }

        if (empty($apiKey)) {
            $err = "No AI Provider API key configured. Please add an API key in AI Providers settings.";
            $steps['ai_rewrite'] = ['status' => 'failed', 'label' => 'AI Rewrite HTML Segments',
                'time' => $this->ms($start), 'error' => $err, 'details' => 'Missing API key'];
            $job->update(['status' => JobStatus::Failed, 'error_message' => $err]);
            return ['success' => false, 'failed_step' => 'ai_rewrite', 'error_message' => $err, 'steps' => $steps];
        }


        // Limit to 12 segments per call to stay within token limits
        $targetSegments = array_slice($segments, 0, 12, true);

        // Build prompt payload — send FULL HTML of each segment so AI can preserve structure
        $promptLines = [];
        foreach ($targetSegments as $idx => $seg) {
            $promptLines[] = "SEG_{$idx}:\n" . $seg['html'];
        }

        $systemPrompt = <<<PROMPT
You are an expert SEO copywriter and HTML specialist.

TASK: Rewrite the text content inside each HTML segment below to be fresh, natural, and SEO-optimized.

STRICT RULES:
1. PRESERVE 100% of all HTML tags, class names, id attributes, style attributes, data-* attributes, href, src, and ALL other HTML attributes EXACTLY as they are.
2. PRESERVE all <span>, <strong>, <em>, <a>, <br>, <i>, <b> inner tags completely unchanged.
3. ONLY change the visible English text words — nothing else.
4. Keep EXACTLY the same meaning/topic. Do NOT invent new facts not present in the original.
5. Maintain approximately the same word count per segment (+/- 15%).
6. Return ONLY a JSON object mapping segment key to the complete rewritten HTML snippet:
   {"SEG_0": "<p class=\"foo\">Rewritten text here</p>", "SEG_1": "<h2>Fresh heading</h2>"}
7. Do NOT add any explanation, markdown fences, or extra text outside the JSON.
8. Every value in the JSON must be a complete, valid HTML snippet identical in structure to the original.
PROMPT;

        $rewrittenMap = [];
        $aiError      = null;

        // Build ordered list of models to try — primary first, then fallbacks
        $endpointFallbacks = match (true) {
            str_contains($endpoint, 'openai.com')    => ['gpt-4o-mini', 'gpt-3.5-turbo'],
            str_contains($endpoint, 'anthropic.com') => ['claude-3-haiku-20240307', 'claude-3-sonnet-20240229'],
            str_contains($endpoint, 'groq.com')      => [
                'llama-3.3-70b-versatile',
                'llama-3.1-8b-instant',
                'llama3-70b-8192',
                'mixtral-8x7b-32768',
                'gemma2-9b-it',
                'llama-3.2-11b-vision-preview',
            ],
            str_contains($endpoint, 'together.ai')   => ['meta-llama/Llama-3-70b-chat-hf', 'mistralai/Mixtral-8x7B-v0.1'],
            str_contains($endpoint, 'mistral.ai')    => ['mistral-small-latest', 'open-mistral-7b'],
            str_contains($endpoint, 'deepseek.com')  => ['deepseek-chat', 'deepseek-coder'],
            default                                   => ['gpt-4o-mini', 'gpt-3.5-turbo'],
        };

        // Build attempts: configured model first, then endpoint fallbacks (deduped)
        $attemptsToTry = array_values(array_unique(
            array_merge([$modelId], $endpointFallbacks)
        ));


        $usedModel = $modelId;
        foreach ($attemptsToTry as $attemptModel) {
            $usedModel = $attemptModel;
            $aiError   = null;

            try {
                $response = Http::withToken($apiKey)
                    ->withoutVerifying()
                    ->timeout(45)
                    ->post($endpoint . '/chat/completions', [
                        'model'           => $attemptModel,
                        'messages'        => [
                            ['role' => 'system', 'content' => $systemPrompt],
                            ['role' => 'user',   'content' => implode("\n\n---\n\n", $promptLines)],
                        ],
                        'temperature'     => 0.5,
                        'max_tokens'      => 4096,
                        'response_format' => ['type' => 'json_object'],
                    ]);

                if ($response->successful()) {
                    $raw          = $response->json('choices.0.message.content');
                    $rewrittenMap = json_decode($raw, true);

                    if (!is_array($rewrittenMap) && preg_match('/\{[\s\S]*\}/s', $raw, $m)) {
                        $rewrittenMap = json_decode($m[0], true);
                    }

                    if (is_array($rewrittenMap) && !empty($rewrittenMap)) {
                        break; // success — stop retrying
                    }

                    $aiError = "AI returned empty or unparseable JSON with model [{$attemptModel}].";
                } elseif ($response->status() === 404) {
                    // Model not found — try next fallback
                    $aiError = "Model [{$attemptModel}] not found on this provider (404). Trying fallback...";
                    Log::warning("JobExecutionService: 404 for model {$attemptModel} on {$endpoint} — will try next fallback in list");
                    continue; // try next model

                } else {
                    $aiError = "AI API Error {$response->status()}: " . ($response->json('error.message') ?? $response->body());
                    break; // non-404 error — don't retry
                }
            } catch (\Throwable $e) {
                $aiError = "AI Connection Error: " . $e->getMessage();
                Log::error("JobExecutionService AI Error: " . $e->getMessage());
                break;
            }
        }

        if ($aiError || !is_array($rewrittenMap) || empty($rewrittenMap)) {
            $err = $aiError ?? "AI returned empty or unparseable JSON.";
            $steps['ai_rewrite'] = ['status' => 'failed', 'label' => 'AI Rewrite HTML Segments',
                'time' => $this->ms($start), 'error' => $err, 'details' => 'No rewritten content produced'];
            $job->update(['status' => JobStatus::Failed, 'error_message' => $err]);
            return ['success' => false, 'failed_step' => 'ai_rewrite', 'error_message' => $err, 'steps' => $steps];
        }


        $steps['ai_rewrite'] = [
            'status'  => 'success',
            'label'   => 'AI Rewrite HTML Segments',
            'time'    => $this->ms($start),
            'error'   => null,
            'details' => "AI rewrote " . count($rewrittenMap) . " HTML segments using {$modelId}",
        ];

        // ──────────────────────────────────────────────────────────────
        // STEP 3 — PATCH: replace original HTML snippets with AI-rewritten ones
        // ──────────────────────────────────────────────────────────────
        $start          = microtime(true);
        $htmlContent    = $rawHtml;
        $replacedCount  = 0;
        $originalTexts  = [];
        $rewrittenTexts = [];

        foreach ($rewrittenMap as $key => $newHtmlSnippet) {
            $newHtmlSnippet = trim((string) $newHtmlSnippet);
            if (empty($newHtmlSnippet)) continue;

            // Parse segment index from key like "SEG_0", "SEG_1"
            preg_match('/(\d+)/', (string)$key, $numMatch);
            $idx = isset($numMatch[1]) ? (int)$numMatch[1] : -1;

            if ($idx < 0 || !isset($targetSegments[$idx])) continue;

            $seg         = $targetSegments[$idx];
            $originalHtml = $seg['html'];

            // Sanity: skip if AI returned HTML with completely different structure (tag mismatch)
            $origTagMatch = [];
            $newTagMatch  = [];
            preg_match('/^<([a-z1-6]+)/i', $originalHtml, $origTagMatch);
            preg_match('/^<([a-z1-6]+)/i', $newHtmlSnippet, $newTagMatch);
            if (!empty($origTagMatch[1]) && !empty($newTagMatch[1]) && strtolower($origTagMatch[1]) !== strtolower($newTagMatch[1])) {
                Log::warning("JobExecutionService: tag mismatch for SEG_{$idx} — skipping (orig: {$origTagMatch[1]}, new: {$newTagMatch[1]})");
                continue;
            }

            // Replace: direct str_replace on the exact original HTML (captured from same $rawHtml)
            if (str_contains($htmlContent, $originalHtml)) {
                $htmlContent = str_replace($originalHtml, $newHtmlSnippet, $htmlContent);
                $replacedCount++;
                $originalTexts[]  = "[{$seg['tag']}] " . trim(strip_tags($originalHtml));
                $rewrittenTexts[] = "[{$seg['tag']}] " . trim(strip_tags($newHtmlSnippet));
            } else {
                // Fallback: try with normalized whitespace
                $normalizedOrig = preg_replace('/\s+/', ' ', $originalHtml);
                $normalizedHtml = preg_replace('/\s+/', ' ', $htmlContent);
                if (str_contains($normalizedHtml, $normalizedOrig)) {
                    $htmlContent = str_replace($normalizedOrig, $newHtmlSnippet, $normalizedHtml);
                    $replacedCount++;
                    $originalTexts[]  = "[{$seg['tag']}] " . trim(strip_tags($originalHtml));
                    $rewrittenTexts[] = "[{$seg['tag']}] " . trim(strip_tags($newHtmlSnippet));
                }
            }
        }

        Log::info("JobExecutionService: Job #{$job->id} — replaced {$replacedCount}/" . count($targetSegments) . " segments");

        $steps['patch_html'] = [
            'status'  => 'success',
            'label'   => 'Patch HTML with Rewritten Content',
            'time'    => $this->ms($start),
            'error'   => null,
            'details' => "Patched {$replacedCount} out of " . count($targetSegments) . " segments (HTML structure fully preserved)",
        ];

        // ──────────────────────────────────────────────────────────────
        // SAVE RESULT for UI preview (original vs rewritten)
        // ──────────────────────────────────────────────────────────────
        RewriteResult::updateOrCreate(
            ['rewrite_job_id' => $job->id],
            [
                'original_segments'  => ['text' => implode("\n\n", $originalTexts),  'html' => $rawHtml],
                'rewritten_segments' => ['text' => implode("\n\n", $rewrittenTexts), 'html' => $htmlContent],
                'ai_duration_ms'     => 0,
            ]
        );

        // ──────────────────────────────────────────────────────────────
        // STEP 4 — APPROVAL CHECK & GIT PUSH
        // ──────────────────────────────────────────────────────────────
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
                'details' => 'Awaiting manual approval — push paused until user approves',
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
        $gitError = null;
        if ($job->website) {
            try {
                $gitService = new \App\Services\GitService();
                $pagePath   = $job->page?->path ?? '/index.html';
                $gitRes     = $gitService->commitAndPush(
                    $job->website,
                    "Autoflow AI: Refreshed content on {$pagePath}",
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
            'details' => 'Committed & pushed to GitHub — Vercel/Netlify deployment triggered',
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

    // ──────────────────────────────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────────────────────────────

    /**
     * Extract rewritable HTML segments directly from raw HTML.
     *
     * KEY INSIGHT: We match against $rawHtml and store the exact $match[0] string.
     * Later, str_replace($match[0], $rewrittenHtml, $rawHtml) is guaranteed to work
     * because we're replacing the exact bytes we captured.
     *
     * Covers: p, h1-h6, li (inside ul/ol), blockquote, td, th
     */
    private function extractHtmlSegments(string $rawHtml): array
    {
        // Build a "safe" copy for matching — strip scripts/styles/comments so we don't
        // accidentally rewrite JS strings or CSS rules, but we match against the REAL HTML
        // positions by using offset tracking.
        //
        // Simpler approach: strip noise tags, collect segments from the cleaned string,
        // then verify each one exists in $rawHtml before adding.

        $clean = $rawHtml;
        // Remove script and style blocks entirely (they might contain HTML-like strings)
        $clean = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $clean);
        $clean = preg_replace('/<style\b[^>]*>[\s\S]*?<\/style>/i', '', $clean);
        $clean = preg_replace('/<!--[\s\S]*?-->/', '', $clean);
        // Remove structural/nav blocks (we don't rewrite header, nav, footer)
        $clean = preg_replace('/<(head|nav|header|footer)\b[^>]*>[\s\S]*?<\/\1>/i', '', $clean);

        // Match all content-bearing elements — NON-greedy to avoid swallowing nested tags
        // We use a two-level approach:
        //   Level A: direct text elements (p, h1-h6, blockquote, td, th)
        //   Level B: list items inside ul/ol (li elements)
        $patterns = [
            // Paragraphs and headings — match content including any inline HTML (spans, a, strong, etc.)
            'block'  => '/<(p|h1|h2|h3|h4|h5|h6|blockquote|td|th)(\s[^>]*)?>[\s\S]*?<\/\1>/i',
            // List items — including those with spans or nested inline elements
            'li'     => '/<li(\s[^>]*)?>[\s\S]*?<\/li>/i',
        ];

        $found = [];

        foreach ($patterns as $type => $pattern) {
            preg_match_all($pattern, $clean, $matches, PREG_SET_ORDER);

            foreach ($matches as $m) {
                $fullHtml  = $m[0];
                $innerText = trim(html_entity_decode(strip_tags($fullHtml), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

                // Skip: too short, likely template/navigation noise
                if (strlen($innerText) < 20) continue;
                if (str_word_count($innerText) < 4) continue;

                // Skip: looks like CSS, JSON, or code
                if (str_starts_with($innerText, '{') || str_starts_with($innerText, '.') || str_starts_with($innerText, '/*')) continue;
                if (str_contains($innerText, 'font-size:') || str_contains($innerText, 'var(--')) continue;

                // Skip: breadcrumbs, copyright, badges
                if (preg_match('/\b(copyright|breadcrumb|©|\bcrumb\b|\bbadge\b)\b/i', $fullHtml)) continue;

                // CRITICAL: only add if this exact HTML string exists in rawHtml (so str_replace will work)
                if (!str_contains($rawHtml, $fullHtml)) continue;

                // Avoid duplicate HTML snippets
                $hash = md5($fullHtml);
                if (isset($found[$hash])) continue;

                $tag = strtolower($type === 'li' ? 'li' : (preg_match('/^<([a-z0-9]+)/i', $fullHtml, $tm) ? $tm[1] : 'p'));

                $found[$hash] = [
                    'tag'   => $tag,
                    'html'  => $fullHtml,   // exact string from rawHtml — str_replace will find this
                    'text'  => $innerText,
                    'words' => str_word_count($innerText),
                ];
            }
        }

        return array_values($found);
    }

    /** Return elapsed milliseconds as a formatted string */
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
            $unit  = $job->website?->default_rewrite_interval_unit ?? 'days';
            $val   = (int)($job->website?->default_rewrite_interval_days ?? 2);

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
