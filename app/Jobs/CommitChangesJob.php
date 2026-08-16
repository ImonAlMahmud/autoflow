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

class CommitChangesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public RewriteJob $rewriteJob
    ) {}

    public function handle(GitService $gitService): void
    {
        Log::info("Starting CommitChangesJob for job #{$this->rewriteJob->id}");

        $this->rewriteJob->status = JobStatus::Committing;
        $this->rewriteJob->save();

        $this->rewriteJob->loadMissing(['website', 'page']);
        $website       = $this->rewriteJob->website;
        $page          = $this->rewriteJob->page;
        $workspacePath = $this->rewriteJob->workspace_path;

        if (! $workspacePath || ! is_dir($workspacePath)) {
            throw new RuntimeException("Workspace path is invalid for job #{$this->rewriteJob->id}.");
        }

        $pageName      = $page->friendly_name ?? $page->path;
        $commitMessage = sprintf('[Autoflow] Refresh content for %s (%s)', $pageName, $page->path);
        $authorName    = config('git.commit_author_name', 'Autoflow Bot');
        $authorEmail   = config('git.commit_author_email', 'bot@autoflow.local');

        $startTime  = microtime(true);
        $newCommit  = $gitService->commit($workspacePath, $commitMessage, $authorName, $authorEmail);
        $durationMs = (int) round((microtime(true) - $startTime) * 1000);

        GitOperation::create([
            'website_id'     => $website->id,
            'rewrite_job_id' => $this->rewriteJob->id,
            'operation'      => GitOperationType::Commit,
            'status'         => 'success',
            'commit_hash'    => $newCommit,
            'branch'         => $website->git_branch,
            'message'        => $commitMessage,
            'duration_ms'    => $durationMs,
        ]);

        $this->rewriteJob->commit_hash = $newCommit;
        $this->rewriteJob->save();

        Log::info("CommitChangesJob completed for job #{$this->rewriteJob->id}. Commit hash: {$newCommit}");
    }
}
