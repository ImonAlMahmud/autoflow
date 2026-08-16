<?php

namespace App\Services\Git;

use App\DTOs\Git\GitConnectionResult;
use App\Models\Website;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class GitService
{
    private string $gitExecutable;
    private int $timeout;

    public function __construct()
    {
        $this->gitExecutable = config('git.executable', 'git');
        $this->timeout       = (int) config('git.timeout', 60);
    }

    /**
     * Test repository connection for a Website.
     * NEVER passes user data directly to shell — uses argument arrays.
     */
    public function testConnection(Website $website): GitConnectionResult
    {
        try {
            $url = $this->buildAuthenticatedUrl($website);

            // Use ls-remote to check connectivity without cloning
            $result = $this->run([
                $this->gitExecutable,
                'ls-remote',
                '--heads',
                '--exit-code',
                $url,
            ]);

            // Parse branches
            $branches = [];
            foreach (explode("\n", trim($result)) as $line) {
                if (preg_match('#refs/heads/(.+)$#', $line, $m)) {
                    $branches[] = $m[1];
                }
            }

            // Check if configured branch exists
            if (!in_array($website->git_branch, $branches) && !empty($branches)) {
                return GitConnectionResult::failure(
                    'branch_not_found',
                    "Branch '{$website->git_branch}' was not found. Available branches: " . implode(', ', $branches)
                );
            }

            // Get current HEAD commit
            $commit = $this->getRemoteHead($url, $website->git_branch);

            return GitConnectionResult::success($commit ?? 'unknown', $branches);

        } catch (\Throwable $e) {
            return $this->classifyGitError($e);
        }
    }

    /**
     * Clone a repository into a target directory.
     */
    public function clone(Website $website, string $targetDir): void
    {
        $url = $this->buildAuthenticatedUrl($website);

        $this->run([
            $this->gitExecutable,
            'clone',
            '--branch', $website->git_branch,
            '--depth', '1',
            $url,
            $targetDir,
        ]);
    }

    /**
     * Pull latest changes in a workspace directory.
     */
    public function pull(string $workspaceDir): void
    {
        $this->run([
            $this->gitExecutable,
            '-C', $workspaceDir,
            'pull',
            '--ff-only',
        ]);
    }

    /**
     * Fetch remote changes to check for divergence.
     */
    public function fetch(string $workspaceDir): void
    {
        $this->run([
            $this->gitExecutable,
            '-C', $workspaceDir,
            'fetch',
            'origin',
        ]);
    }

    /**
     * Get the current HEAD commit hash in a workspace.
     */
    public function getCurrentCommit(string $workspaceDir): string
    {
        return trim($this->run([
            $this->gitExecutable,
            '-C', $workspaceDir,
            'rev-parse',
            'HEAD',
        ]));
    }

    /**
     * Check if local branch has diverged from remote.
     */
    public function hasDiverged(string $workspaceDir, string $branch): bool
    {
        try {
            $this->fetch($workspaceDir);

            $local  = trim($this->run([$this->gitExecutable, '-C', $workspaceDir, 'rev-parse', 'HEAD']));
            $remote = trim($this->run([$this->gitExecutable, '-C', $workspaceDir, 'rev-parse', "origin/{$branch}"]));

            return $local !== $remote;
        } catch (\Throwable) {
            // If we can't determine, assume diverged for safety
            return true;
        }
    }

    /**
     * Stage all changes and create a commit.
     */
    public function commit(string $workspaceDir, string $message, string $authorName = 'Autoflow Bot', string $authorEmail = 'bot@autoflow.local'): string
    {
        // Configure identity for this repo
        $this->run([$this->gitExecutable, '-C', $workspaceDir, 'config', 'user.name', $authorName]);
        $this->run([$this->gitExecutable, '-C', $workspaceDir, 'config', 'user.email', $authorEmail]);

        // Stage modified files
        $this->run([$this->gitExecutable, '-C', $workspaceDir, 'add', '-A']);

        // Commit
        $this->run([$this->gitExecutable, '-C', $workspaceDir, 'commit', '--message', $message]);

        // Return new commit hash
        return $this->getCurrentCommit($workspaceDir);
    }

    /**
     * Push to remote. NEVER force-pushes.
     */
    public function push(string $workspaceDir, string $branch): void
    {
        // Safety: never force push
        $this->run([
            $this->gitExecutable,
            '-C', $workspaceDir,
            'push',
            'origin',
            $branch,
            // deliberately NO --force flag
        ]);
    }

    /**
     * Get diff of staged changes.
     */
    public function getDiff(string $workspaceDir): string
    {
        return $this->run([
            $this->gitExecutable,
            '-C', $workspaceDir,
            'diff',
            'HEAD',
        ]);
    }

    /**
     * Build an authenticated URL for HTTPS token auth.
     * For SSH, the URL is returned as-is (auth is via SSH agent/key).
     */
    private function buildAuthenticatedUrl(Website $website): string
    {
        if ($website->git_auth_method->value === 'ssh') {
            return $website->git_repository_url;
        }

        // HTTPS token: embed token in URL
        $token = $website->git_access_token;

        if (!$token) {
            return $website->git_repository_url;
        }

        // Parse URL and inject credentials
        $parsed = parse_url($website->git_repository_url);

        if (!$parsed) {
            throw new RuntimeException('Invalid repository URL.');
        }

        $scheme = $parsed['scheme'] ?? 'https';
        $host   = $parsed['host'];
        $path   = $parsed['path'] ?? '/';

        return "{$scheme}://oauth2:{$token}@{$host}{$path}";
    }

    /**
     * Get remote HEAD commit hash for a branch.
     */
    private function getRemoteHead(string $url, string $branch): ?string
    {
        try {
            $output = $this->run([
                $this->gitExecutable,
                'ls-remote',
                $url,
                "refs/heads/{$branch}",
            ]);

            $lines = explode("\n", trim($output));
            if (!empty($lines[0])) {
                return substr($lines[0], 0, 40);
            }
        } catch (\Throwable) {}

        return null;
    }

    /**
     * Run a git command safely using process arrays (never shell injection).
     */
    private function run(array $command, ?string $cwd = null): string
    {
        $process = new Process($command, $cwd);
        $process->setTimeout($this->timeout);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    /**
     * Classify a git exception into a user-friendly result.
     */
    private function classifyGitError(\Throwable $e): GitConnectionResult
    {
        $message = $e->getMessage();

        if (str_contains($message, 'Authentication failed') || str_contains($message, '401')) {
            return GitConnectionResult::failure('auth_failed', 'Authentication failed.');
        }
        if (str_contains($message, 'Repository not found') || str_contains($message, '404')) {
            return GitConnectionResult::failure('repo_not_found', 'Repository not found.');
        }
        if (str_contains($message, 'Permission denied') || str_contains($message, '403')) {
            return GitConnectionResult::failure('permission_denied', 'Permission denied.');
        }
        if (str_contains($message, 'Could not resolve host') || str_contains($message, 'Connection refused')) {
            return GitConnectionResult::failure('network_error', 'Cannot reach Git host.');
        }

        Log::warning('Git error', ['message' => $message]);
        return GitConnectionResult::failure('unknown_error', 'Git error: ' . $message);
    }
}
