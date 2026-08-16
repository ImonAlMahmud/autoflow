<?php

namespace App\Services;

use App\Models\Website;
use Illuminate\Support\Facades\Log;

class GitService
{
    public function commitAndPush(Website $website, string $commitMessage = 'Autoflow AI: Content refresh update'): array
    {
        $dir = $website->local_production_path;

        if (empty($dir) || !is_dir($dir)) {
            return [
                'success' => false,
                'message' => 'Local website path does not exist.',
            ];
        }

        try {
            // Change directory to the local website folder
            $oldCwd = getcwd();
            chdir($dir);

            // 1. Initialize git if not already initialized
            if (!file_exists($dir . DIRECTORY_SEPARATOR . '.git')) {
                shell_exec('git init');
            }

            // 2. Git config user (using website configured author fields)
            $authorName = addslashes($website->git_author_name ?: 'Imon Mahmud');
            $authorEmail = addslashes($website->git_author_email ?: 'imon.mahmud4@gmail.com');
            shell_exec('git config user.name "' . $authorName . '"');
            shell_exec('git config user.email "' . $authorEmail . '"');

            // 3. Git pull remote changes to prevent fast-forward conflict
            shell_exec('git pull origin main --rebase 2>&1');

            // 4. Git Add
            shell_exec('git add .');

            // 5. Git Commit
            $commitOutput = shell_exec('git commit -m "' . addslashes($commitMessage) . '" 2>&1');
            Log::info("Git commit output for {$website->name}: {$commitOutput}");

            if (str_contains(strtolower($commitOutput ?? ''), 'nothing to commit')) {
                chdir($oldCwd);
                return [
                    'success' => true,
                    'message' => 'File updated (No git changes detected to commit).',
                ];
            }

            // 6. Git Push
            $pushOutput = shell_exec('git push origin main 2>&1');

            if (empty($pushOutput) || str_contains($pushOutput, 'fatal')) {
                // Try basic git push fallback
                $pushOutput = shell_exec('git push 2>&1');
            }

            chdir($oldCwd);

            Log::info("Git push output for {$website->name}: {$pushOutput}");

            return [
                'success' => true,
                'message' => 'Git Commit & Push executed: ' . trim($pushOutput),
            ];

        } catch (\Throwable $e) {
            Log::error("Git Execution Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Git error: ' . $e->getMessage(),
            ];
        }
    }
}
