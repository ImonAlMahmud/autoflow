<?php

namespace App\Services\Git;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class RepositoryWorkspaceService
{
    private string $workspaceRoot;
    private int $maxAgeHours;

    public function __construct()
    {
        $this->workspaceRoot = config('git.workspace_root', '/opt/ai-content-refresh/workspaces');
        $this->maxAgeHours   = (int) config('git.workspace_max_age_hours', 24);
    }

    /**
     * Create an isolated workspace directory for a job.
     * Returns the absolute path to the workspace.
     */
    public function create(int $jobId): string
    {
        $workspacePath = $this->buildPath($jobId);

        if (!is_dir($workspacePath)) {
            if (!mkdir($workspacePath, 0755, true)) {
                throw new RuntimeException("Failed to create workspace directory: {$workspacePath}");
            }
        }

        return $workspacePath;
    }

    /**
     * Safely delete a workspace directory.
     */
    public function cleanup(string $workspacePath): void
    {
        if (!$this->isAllowedPath($workspacePath)) {
            Log::warning('Refused to delete workspace outside allowed root', [
                'path' => $workspacePath,
                'root' => $this->workspaceRoot,
            ]);
            return;
        }

        if (is_dir($workspacePath)) {
            $this->deleteDirectory($workspacePath);
        }
    }

    /**
     * Clean up all workspaces older than the configured max age.
     */
    public function cleanupAbandoned(): int
    {
        $count = 0;

        if (!is_dir($this->workspaceRoot)) {
            return 0;
        }

        $entries = scandir($this->workspaceRoot);

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $path = $this->workspaceRoot . DIRECTORY_SEPARATOR . $entry;

            if (!is_dir($path)) {
                continue;
            }

            $mtime = filemtime($path);
            $ageHours = (time() - $mtime) / 3600;

            if ($ageHours > $this->maxAgeHours) {
                $this->cleanup($path);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Build the workspace path for a job.
     */
    public function buildPath(int $jobId): string
    {
        return $this->workspaceRoot . DIRECTORY_SEPARATOR . 'job-' . $jobId;
    }

    /**
     * Verify the workspace root exists; create it if not.
     */
    public function ensureWorkspaceRoot(): void
    {
        if (!is_dir($this->workspaceRoot)) {
            if (!mkdir($this->workspaceRoot, 0755, true)) {
                throw new RuntimeException("Cannot create workspace root: {$this->workspaceRoot}");
            }
        }
    }

    /**
     * Security: ensure path is within the allowed workspace root.
     */
    private function isAllowedPath(string $path): bool
    {
        $realRoot = realpath($this->workspaceRoot);
        $realPath = realpath($path);

        if ($realRoot === false || $realPath === false) {
            // Can't resolve; path may not exist yet — do string comparison
            return str_starts_with(
                str_replace('\\', '/', $path),
                str_replace('\\', '/', $this->workspaceRoot)
            );
        }

        return str_starts_with(
            str_replace('\\', '/', $realPath),
            str_replace('\\', '/', $realRoot)
        );
    }

    /**
     * Recursively delete a directory.
     */
    private function deleteDirectory(string $dir): void
    {
        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        rmdir($dir);
    }
}
