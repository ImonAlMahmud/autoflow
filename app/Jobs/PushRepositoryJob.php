<?php

namespace App\Jobs;

use App\Enums\GitOperationType;
use App\Enums\JobStatus;
use App\Models\GitOperation;
use App\Models\RewriteJob;
use App\Services\Git\GitService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PushRepositoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public RewriteJob $rewriteJob
    ) {}

    public function handle(GitService $gitService): void
    {
        Log::info("Starting PushRepositoryJob for job #{$this->rewriteJob->id}");

        $this->rewriteJob->status = JobStatus::Pushing;
        $this->rewriteJob->save();

        $this->rewriteJob->loadMissing(['website', 'page']);
        $website       = $this->rewriteJob->website;
        $page          = $this->rewriteJob->page;
        $workspacePath = $this->rewriteJob->workspace_path;

        if (! $workspacePath || ! is_dir($workspacePath)) {
            throw new RuntimeException("Workspace directory missing for job #{$this->rewriteJob->id}.");
        }

        $branch = $website->git_branch;

        if ($website->auto_push_enabled) {
            $startTime  = microtime(true);
            $gitService->push($workspacePath, $branch);
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);

            GitOperation::create([
                'website_id'     => $website->id,
                'rewrite_job_id' => $this->rewriteJob->id,
                'operation'      => GitOperationType::Push,
                'status'         => 'success',
                'commit_hash'    => $this->rewriteJob->commit_hash,
                'branch'         => $branch,
                'message'        => "Pushed branch '{$branch}' to remote",
                'duration_ms'    => $durationMs,
            ]);
            Log::info("PushRepositoryJob pushed changes for job #{$this->rewriteJob->id} to branch {$branch}.");
        } else {
            Log::info("Auto push disabled for website #{$website->id}. Skipping Git push.");
        }

        // Mark job as completed
        $this->rewriteJob->status           = JobStatus::Completed;
        $this->rewriteJob->finished_at       = now();
        $this->rewriteJob->duration_seconds = $this->rewriteJob->started_at
            ? (int) $this->rewriteJob->finished_at->diffInSeconds($this->rewriteJob->started_at)
            : null;
        $this->rewriteJob->save();

        // Update website page schedule and content hash
        if ($page) {
            $page->last_rewrite_at = now();
            $page->next_rewrite_at = now()->addDays($page->effective_interval_days);
            if (! empty($this->rewriteJob->rewritten_content_hash)) {
                $page->content_hash = $this->rewriteJob->rewritten_content_hash;
            }
            $page->save();
        }

        Log::info("PushRepositoryJob completed for job #{$this->rewriteJob->id}. Job marked as Completed.");
    }
}
