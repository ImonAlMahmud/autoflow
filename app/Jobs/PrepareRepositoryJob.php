<?php

namespace App\Jobs;

use App\Enums\GitOperationType;
use App\Enums\JobStatus;
use App\Models\GitOperation;
use App\Models\RewriteJob;
use App\Services\Git\GitService;
use App\Services\Git\RepositoryWorkspaceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PrepareRepositoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public RewriteJob $rewriteJob
    ) {}

    public function handle(RepositoryWorkspaceService $workspaceService, GitService $gitService): void
    {
        Log::info("Starting PrepareRepositoryJob for job #{$this->rewriteJob->id}");

        $this->rewriteJob->started_at ??= now();
        $this->rewriteJob->status = JobStatus::Preparing;
        $this->rewriteJob->save();

        $this->rewriteJob->loadMissing(['website', 'page']);
        $website = $this->rewriteJob->website;
        $page    = $this->rewriteJob->page;

        if (! $website) {
            throw new RuntimeException("RewriteJob #{$this->rewriteJob->id} has no associated Website.");
        }

        if (! $page) {
            throw new RuntimeException("RewriteJob #{$this->rewriteJob->id} has no associated WebsitePage.");
        }

        // Create isolated workspace directory
        $workspacePath = $workspaceService->create($this->rewriteJob->id);
        $this->rewriteJob->workspace_path = $workspacePath;
        $this->rewriteJob->save();

        // Clone repository into workspace
        $startTime = microtime(true);
        $gitService->clone($website, $workspacePath);
        $durationMs = (int) round((microtime(true) - $startTime) * 1000);

        // Fetch current commit hash
        $commitHash = $gitService->getCurrentCommit($workspacePath);
        $this->rewriteJob->original_commit_hash = $commitHash;
        $this->rewriteJob->save();

        // Record GitOperation
        GitOperation::create([
            'website_id'     => $website->id,
            'rewrite_job_id' => $this->rewriteJob->id,
            'operation'      => GitOperationType::Clone,
            'status'         => 'success',
            'commit_hash'    => $commitHash,
            'branch'         => $website->git_branch,
            'message'        => "Cloned repository branch '{$website->git_branch}' into workspace",
            'duration_ms'    => $durationMs,
        ]);

        // Verify target HTML file exists within cloned workspace
        $targetFilePath = rtrim($workspacePath, '/\\') . DIRECTORY_SEPARATOR . ltrim($page->path, '/\\');

        if (! file_exists($targetFilePath)) {
            throw new RuntimeException("Target page file [{$page->path}] not found in cloned workspace repository.");
        }

        Log::info("PrepareRepositoryJob completed successfully for job #{$this->rewriteJob->id}");
    }
}
