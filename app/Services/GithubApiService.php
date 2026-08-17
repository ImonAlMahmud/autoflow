<?php

namespace App\Services;

use App\Models\Website;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GithubApiService
{
    /**
     * Parse "owner" and "repo" from various formats of GitHub repository URL:
     * - https://github.com/owner/repo.git
     * - https://github.com/owner/repo
     * - git@github.com:owner/repo.git
     * - owner/repo
     */
    public function parseRepo(string $repoUrl): ?array
    {
        $cleaned = trim($repoUrl);
        $cleaned = preg_replace('/\.git$/i', '', $cleaned);

        if (preg_match('/github\.com[\/:]([^\/]+)\/([^\/]+)/i', $cleaned, $matches)) {
            return [
                'owner' => trim($matches[1]),
                'repo' => trim($matches[2]),
            ];
        }

        $parts = explode('/', trim($cleaned, '/'));
        if (count($parts) === 2) {
            return [
                'owner' => $parts[0],
                'repo' => $parts[1],
            ];
        }

        return null;
    }

    /**
     * Resolve effective GitHub token (Website specific or Global System Setting)
     */
    private function resolveToken(?Website $website): ?string
    {
        if ($website && !empty($website->git_access_token)) {
            return $website->git_access_token;
        }

        return \App\Models\SystemSetting::get('global_github_token', '') ?: config('services.github.token', null);
    }

    /**
     * Fetch file content and its GitHub SHA from repository
     */
    public function getFileContent(Website $website, string $filePath): ?array
    {
        try {
            $repoInfo = $this->parseRepo($website->git_repository_url ?? '');
            if (!$repoInfo) {
                Log::error("GitHub API: Invalid repository URL {$website->git_repository_url}");
                return null;
            }

            $token = $this->resolveToken($website);
            $branch = $website->git_branch ?: 'main';
            $cleanPath = ltrim($filePath, '/\\');

            $url = "https://api.github.com/repos/{$repoInfo['owner']}/{$repoInfo['repo']}/contents/{$cleanPath}?ref={$branch}";

            $request = Http::withHeaders([
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'Autoflow-AI-Agent',
            ])->timeout(15);

            if ($token) {
                $request->withToken($token);
            }

            $response = $request->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $content = isset($data['content']) ? base64_decode($data['content']) : null;
                return [
                    'content' => $content,
                    'sha' => $data['sha'] ?? null,
                    'size' => $data['size'] ?? 0,
                ];
            }

            Log::warning("GitHub API: Could not fetch {$cleanPath} from {$repoInfo['owner']}/{$repoInfo['repo']} ({$response->status()}): " . $response->body());
            return null;
        } catch (\Throwable $e) {
            Log::error("GitHub API getFileContent exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * List all HTML files in repository (Recursive Tree Search)
     */
    public function listHtmlFiles(Website $website): array
    {
        try {
            $repoInfo = $this->parseRepo($website->git_repository_url ?? '');
            if (!$repoInfo) {
                return [];
            }

            $token = $this->resolveToken($website);
            $branch = $website->git_branch ?: 'main';

            $url = "https://api.github.com/repos/{$repoInfo['owner']}/{$repoInfo['repo']}/git/trees/{$branch}?recursive=1";

            $request = Http::withHeaders([
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'Autoflow-AI-Agent',
            ])->timeout(20);

            if ($token) {
                $request->withToken($token);
            }

            $response = $request->get($url);

            $htmlFiles = [];
            if ($response->successful()) {
                $tree = $response->json('tree') ?? [];
                foreach ($tree as $item) {
                    if (($item['type'] ?? '') === 'blob' && str_ends_with(strtolower($item['path'] ?? ''), '.html')) {
                        $htmlFiles[] = '/' . ltrim($item['path'], '/');
                    }
                }
            } else {
                Log::warning("GitHub API listHtmlFiles failed ({$response->status()}): " . $response->body());
            }

            return $htmlFiles;
        } catch (\Throwable $e) {
            Log::error("GitHub API listHtmlFiles exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update file in repository directly via GitHub REST API (Triggers Vercel/Netlify auto-deployment)
     */
    public function updateFile(Website $website, string $filePath, string $newContent, string $commitMessage = 'Autoflow AI: Content refresh'): array
    {
        try {
            $repoInfo = $this->parseRepo($website->git_repository_url ?? '');
            if (!$repoInfo) {
                return [
                    'success' => false,
                    'message' => 'Invalid GitHub repository URL format.',
                ];
            }

            $token = $this->resolveToken($website);
            if (empty($token)) {
                return [
                    'success' => false,
                    'message' => 'GitHub Personal Access Token (PAT) is required. Set it globally in Settings or on this Website.',
                ];
            }

            $branch = $website->git_branch ?: 'main';
            $cleanPath = ltrim($filePath, '/\\');

            // 1. Get current SHA
            $fileData = $this->getFileContent($website, $cleanPath);
            $currentSha = $fileData['sha'] ?? null;

            $url = "https://api.github.com/repos/{$repoInfo['owner']}/{$repoInfo['repo']}/contents/{$cleanPath}";

            $payload = [
                'message' => $commitMessage,
                'content' => base64_encode($newContent),
                'branch' => $branch,
                'committer' => [
                    'name' => $website->git_author_name ?: 'Autoflow AI Bot',
                    'email' => $website->git_author_email ?: 'bot@autoflow.ideomet.com',
                ],
                'author' => [
                    'name' => $website->git_author_name ?: 'Autoflow AI Bot',
                    'email' => $website->git_author_email ?: 'bot@autoflow.ideomet.com',
                ],
            ];

            if ($currentSha) {
                $payload['sha'] = $currentSha;
            }

            $response = Http::withHeaders([
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'Autoflow-AI-Agent',
            ])
            ->withToken($token)
            ->timeout(25)
            ->put($url, $payload);

            if ($response->successful()) {
                $commitSha = $response->json('commit.sha');
                Log::info("GitHub API Commit Success: Committed to {$cleanPath} on {$repoInfo['owner']}/{$repoInfo['repo']} [SHA: {$commitSha}]");

                // Record GitOperation entry for Dashboard Live Stream & Chart Velocity
                try {
                    \App\Models\GitOperation::create([
                        'website_id' => $website->id,
                        'operation' => \App\Enums\GitOperationType::Push,
                        'status' => 'success',
                        'commit_hash' => substr($commitSha ?? md5(time()), 0, 8),
                        'branch' => $branch,
                        'message' => $commitMessage,
                        'duration_ms' => 450,
                    ]);
                } catch (\Throwable $e) {
                    Log::warning("GitOperation log save notice: " . $e->getMessage());
                }

                return [
                    'success' => true,
                    'message' => "Committed and pushed to GitHub ({$branch}) successfully! SHA: " . substr($commitSha, 0, 7),
                    'commit_sha' => $commitSha,
                ];
            }

            $err = $response->json('message') ?? $response->body();
            Log::error("GitHub API Commit Failed ({$response->status()}): {$err}");

            return [
                'success' => false,
                'message' => "GitHub API Error ({$response->status()}): {$err}",
            ];
        } catch (\Throwable $e) {
            Log::error("GitHub API updateFile exception: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "GitHub API Exception: " . $e->getMessage(),
            ];
        }
    }
}
