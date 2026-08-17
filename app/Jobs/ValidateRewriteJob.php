<?php

namespace App\Jobs;

use App\Enums\ApprovalMode;
use App\Enums\JobStatus;
use App\Enums\ValidationStatus;
use App\Models\RewriteJob;
use App\Models\ValidationResult;
use App\Services\Content\ProtectedValueService;
use App\Services\Rewrite\RewriteValidationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ValidateRewriteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public RewriteJob $rewriteJob
    ) {}

    public function handle(
        RewriteValidationService $validationService,
        ProtectedValueService $protectedValueService
    ): void {
        Log::info("Starting ValidateRewriteJob for job #{$this->rewriteJob->id}");

        $this->rewriteJob->status = JobStatus::Validating;
        $this->rewriteJob->save();

        $this->rewriteJob->loadMissing(['website', 'page', 'result']);
        $result  = $this->rewriteJob->result;
        $website = $this->rewriteJob->website;
        $page    = $this->rewriteJob->page;

        if (! $result || empty($result->rewritten_segments)) {
            throw new RuntimeException("No rewritten content found for validation in job #{$this->rewriteJob->id}.");
        }

        $targetFilePath = rtrim($this->rewriteJob->workspace_path, '/\\') . DIRECTORY_SEPARATOR . ltrim($page->path, '/\\');
        $rewrittenHtml  = file_exists($targetFilePath) ? file_get_contents($targetFilePath) : '';
        $originalHtml   = ''; // Compare HTML structures

        // Extract protected values to validate against
        $customTerms        = array_merge($website->protected_terms ?? [], $page->protected_values ?? []);
        $extractedProtected = $protectedValueService->extractFromSegments($result->original_segments);
        $protectedValues    = $protectedValueService->mergeWithCustomTerms($extractedProtected, $customTerms);

        $report = $validationService->validate(
            $result->original_segments,
            $result->rewritten_segments,
            $originalHtml,
            $rewrittenHtml,
            $protectedValues
        );

        // Store or update ValidationResult record
        ValidationResult::updateOrCreate(
            ['rewrite_job_id' => $this->rewriteJob->id],
            [
                'json_validity'        => $report->checks['json_validity']['passed'] ?? false,
                'segment_completeness' => $report->checks['segment_completeness']['passed'] ?? false,
                'protected_values'     => $report->checks['protected_values']['passed'] ?? false,
                'html_structure'       => $report->checks['html_structure']['passed'] ?? false,
                'links_preserved'      => $report->checks['links_preserved']['passed'] ?? false,
                'word_count'           => $report->checks['word_count']['passed'] ?? false,
                'language_check'       => $report->checks['language_check']['passed'] ?? true,
                'content_quality'      => $report->checks['content_quality']['passed'] ?? false,
                'overall_passed'       => $report->passed,
                'details'              => $report->toArray(),
            ]
        );

        if ($report->passed) {
            $this->rewriteJob->validation_status = ValidationStatus::Passed;

            $effectiveApproval = $page->effective_approval_mode;

            if ($effectiveApproval === ApprovalMode::Manual) {
                $this->rewriteJob->status = JobStatus::PendingApproval;
                Log::info("Job #{$this->rewriteJob->id} validation passed. Placed in PendingApproval mode.");
                \App\Services\EmailNotificationService::notifyPendingApproval($this->rewriteJob);
            } else {
                Log::info("Job #{$this->rewriteJob->id} validation passed. Continuing automatic pipeline.");
            }
            $this->rewriteJob->save();
        } else {
            $failureMsg = 'Validation failed: ' . implode('; ', array_column($report->failures, 'message'));
            $this->rewriteJob->validation_status = ValidationStatus::Failed;
            $this->rewriteJob->status            = JobStatus::Failed;
            $this->rewriteJob->failure_reason     = $failureMsg;
            $this->rewriteJob->save();

            \App\Services\EmailNotificationService::notifyJobFailed($this->rewriteJob, $failureMsg);

            Log::warning("ValidateRewriteJob failed for job #{$this->rewriteJob->id}: {$failureMsg}");
            throw new RuntimeException($failureMsg);
        }
    }
}
