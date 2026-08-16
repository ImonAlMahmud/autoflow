<?php

namespace App\Services\Pipeline;

use App\Enums\JobStatus;
use App\Jobs\CleanupWorkspaceJob;
use App\Jobs\CommitChangesJob;
use App\Jobs\ExtractContentJob;
use App\Jobs\PrepareRepositoryJob;
use App\Jobs\PushRepositoryJob;
use App\Jobs\RewriteContentJob;
use App\Jobs\ValidateRewriteJob;
use App\Models\RewriteJob;
use App\Services\Git\RepositoryWorkspaceService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class JobPipelineService
{
    public function __construct(
        private readonly RepositoryWorkspaceService $workspaceService,
    ) {}

    /**
     * Run the complete rewrite pipeline synchronously for a job.
     */
    public function run(RewriteJob $job): void
    {
        Log::info("JobPipelineService: Starting pipeline for job #{$job->id}");

        try {
            // Step 1: Prepare Repository & Workspace
            $prepareJob = new PrepareRepositoryJob($job);
            app()->call([$prepareJob, 'handle']);
            $job->refresh();

            // Step 2: Extract Content & Protected Values
            $extractJob = new ExtractContentJob($job);
            app()->call([$extractJob, 'handle']);
            $job->refresh();

            // Step 3: Rewrite Content with AI
            $rewriteJobStep = new RewriteContentJob($job);
            app()->call([$rewriteJobStep, 'handle']);
            $job->refresh();

            // Step 4: Validate Rewrite Results
            $validateJob = new ValidateRewriteJob($job);
            app()->call([$validateJob, 'handle']);
            $job->refresh();

            // Check if job paused for manual approval
            if ($job->status === JobStatus::PendingApproval) {
                Log::info("JobPipelineService: Job #{$job->id} paused in PendingApproval state for user review.");
                return;
            }

            // Step 5: Commit Changes
            $commitJob = new CommitChangesJob($job);
            app()->call([$commitJob, 'handle']);
            $job->refresh();

            // Step 6: Push Repository
            $pushJob = new PushRepositoryJob($job);
            app()->call([$pushJob, 'handle']);
            $job->refresh();

            // Step 7: Cleanup Workspace
            $cleanupJob = new CleanupWorkspaceJob($job);
            app()->call([$cleanupJob, 'handle']);

            Log::info("JobPipelineService: Pipeline completed successfully for job #{$job->id}");

        } catch (Throwable $e) {
            $this->fail($job, $e->getMessage(), $e);
            throw $e;
        }
    }

    /**
     * Dispatch the pipeline steps as a queued job chain.
     */
    public function dispatch(RewriteJob $job): void
    {
        $job->status = JobStatus::Queued;
        $job->save();

        Log::info("JobPipelineService: Dispatching queued pipeline chain for job #{$job->id}");

        Bus::chain([
            new PrepareRepositoryJob($job),
            new ExtractContentJob($job),
            new RewriteContentJob($job),
            new ValidateRewriteJob($job),
            new CommitChangesJob($job),
            new PushRepositoryJob($job),
            new CleanupWorkspaceJob($job),
        ])->dispatch();
    }

    /**
     * Resume pipeline execution for a job in PendingApproval state after manual approval.
     */
    public function resume(RewriteJob $job): void
    {
        Log::info("JobPipelineService: Resuming job #{$job->id} after manual approval.");

        if ($job->status !== JobStatus::PendingApproval) {
            Log::warning("JobPipelineService: Cannot resume job #{$job->id} with status [{$job->status->value}].");
            return;
        }

        try {
            // Step 5: Commit Changes
            $commitJob = new CommitChangesJob($job);
            app()->call([$commitJob, 'handle']);
            $job->refresh();

            // Step 6: Push Repository
            $pushJob = new PushRepositoryJob($job);
            app()->call([$pushJob, 'handle']);
            $job->refresh();

            // Step 7: Cleanup Workspace
            $cleanupJob = new CleanupWorkspaceJob($job);
            app()->call([$cleanupJob, 'handle']);

            Log::info("JobPipelineService: Resumed pipeline completed successfully for job #{$job->id}");

        } catch (Throwable $e) {
            $this->fail($job, $e->getMessage(), $e);
            throw $e;
        }
    }

    /**
     * Mark job as failed and clean up workspace.
     */
    public function fail(RewriteJob $job, string $reason, ?Throwable $e = null): void
    {
        Log::error("JobPipelineService: Job #{$job->id} failed: {$reason}", [
            'exception' => $e?->getMessage(),
        ]);

        $job->refresh();
        $job->status           = JobStatus::Failed;
        $job->failure_reason   = $reason;
        $job->finished_at       = now();
        $job->duration_seconds = $job->started_at
            ? (int) now()->diffInSeconds($job->started_at)
            : null;
        $job->save();

        if ($job->workspace_path && is_dir($job->workspace_path)) {
            try {
                $this->workspaceService->cleanup($job->workspace_path);
            } catch (Throwable $cleanupEx) {
                Log::warning("Failed to cleanup workspace for failed job #{$job->id}: " . $cleanupEx->getMessage());
            }
        }
    }

    /**
     * Cancel an active or pending job and clean up workspace.
     */
    public function cancel(RewriteJob $job, ?string $reason = null): void
    {
        Log::info("JobPipelineService: Cancelling job #{$job->id}");

        $job->status         = JobStatus::Cancelled;
        $job->failure_reason = $reason ?? 'Cancelled by user or system.';
        $job->finished_at     = now();
        $job->save();

        if ($job->workspace_path && is_dir($job->workspace_path)) {
            $this->workspaceService->cleanup($job->workspace_path);
        }
    }
}
