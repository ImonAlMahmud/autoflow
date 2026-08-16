<?php

namespace App\Jobs;

use App\Models\RewriteJob;
use App\Services\Git\RepositoryWorkspaceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CleanupWorkspaceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public RewriteJob $rewriteJob
    ) {}

    public function handle(RepositoryWorkspaceService $workspaceService): void
    {
        Log::info("Starting CleanupWorkspaceJob for job #{$this->rewriteJob->id}");

        $workspacePath = $this->rewriteJob->workspace_path;

        if ($workspacePath && is_dir($workspacePath)) {
            $workspaceService->cleanup($workspacePath);
            Log::info("Cleaned up workspace at [{$workspacePath}] for job #{$this->rewriteJob->id}.");
        } else {
            Log::info("No workspace directory found to clean up for job #{$this->rewriteJob->id}.");
        }
    }
}
