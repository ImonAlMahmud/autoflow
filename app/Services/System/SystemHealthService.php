<?php

namespace App\Services\System;

use App\Services\AI\AIManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Process;
use Throwable;

class SystemHealthService
{
    public function __construct(
        private readonly AIManager $aiManager,
    ) {}

    /**
     * Run all health checks and return results.
     */
    public function check(): array
    {
        return [
            'database'       => $this->checkDatabase(),
            'redis'          => $this->checkRedis(),
            'queue'          => $this->checkQueue(),
            'scheduler'      => $this->checkScheduler(),
            'ollama'         => $this->checkOllama(),
            'git'            => $this->checkGit(),
            'disk'           => $this->checkDisk(),
            'workspace'      => $this->checkWorkspace(),
        ];
    }

    public function checkDatabase(): array
    {
        try {
            DB::select('SELECT 1');
            return $this->healthy('Database');
        } catch (Throwable $e) {
            return $this->unhealthy('Database', $e->getMessage());
        }
    }

    public function checkRedis(): array
    {
        try {
            $pong = Cache::store('redis')->get('health_check_' . time());
            Cache::store('redis')->put('health_check', 1, 5);
            return $this->healthy('Redis');
        } catch (Throwable $e) {
            return $this->unhealthy('Redis', $e->getMessage());
        }
    }

    public function checkQueue(): array
    {
        try {
            // Check if there are queue workers running by looking at failed jobs or recent job processing
            // This is a heuristic check
            $recentFailed = DB::table('failed_jobs')
                ->where('failed_at', '>', now()->subMinutes(5))
                ->count();

            if ($recentFailed > 5) {
                return $this->warning('Queue Workers', 'High failure rate in last 5 minutes.');
            }

            return $this->healthy('Queue Workers');
        } catch (Throwable $e) {
            return $this->warning('Queue Workers', 'Cannot determine queue status.');
        }
    }

    public function checkScheduler(): array
    {
        try {
            $lastRun = Cache::get('scheduler_last_run');

            if (!$lastRun) {
                return $this->warning('Scheduler', 'No scheduler heartbeat found. Is the cron configured?');
            }

            $minutesAgo = (int) ((time() - $lastRun) / 60);

            if ($minutesAgo > 10) {
                return $this->warning('Scheduler', "Last run {$minutesAgo} minutes ago.");
            }

            return $this->healthy('Scheduler', "Last run {$minutesAgo} minute(s) ago.");
        } catch (Throwable $e) {
            return $this->warning('Scheduler', 'Cannot determine scheduler status.');
        }
    }

    public function checkOllama(): array
    {
        try {
            $provider = $this->aiManager->provider('ollama');
            $health   = $provider->healthCheck();

            if (!$health->online) {
                return $this->unhealthy('Ollama', $health->error ?? 'Offline');
            }

            $detail = "Response: {$health->responseTimeMs}ms";
            if (!empty($health->availableModels)) {
                $detail .= ' | Models: ' . count($health->availableModels);
            }

            return $this->healthy('Ollama', $detail, [
                'response_ms'     => $health->responseTimeMs,
                'models'          => $health->availableModels,
            ]);
        } catch (Throwable $e) {
            return $this->unhealthy('Ollama', $e->getMessage());
        }
    }

    public function checkGit(): array
    {
        try {
            $process = new Process([config('git.executable', 'git'), '--version']);
            $process->run();

            if (!$process->isSuccessful()) {
                return $this->unhealthy('Git', 'git command not found.');
            }

            $version = trim($process->getOutput());
            return $this->healthy('Git', $version);
        } catch (Throwable $e) {
            return $this->unhealthy('Git', $e->getMessage());
        }
    }

    public function checkDisk(): array
    {
        try {
            $root  = base_path();
            $free  = disk_free_space($root);
            $total = disk_total_space($root);

            if ($free === false || $total === false) {
                return $this->warning('Disk', 'Cannot read disk space.');
            }

            $usedPercent = round((($total - $free) / $total) * 100, 1);
            $freeGb      = round($free / 1073741824, 2);

            if ($usedPercent > 90) {
                return $this->unhealthy('Disk', "{$usedPercent}% used ({$freeGb} GB free)");
            }
            if ($usedPercent > 80) {
                return $this->warning('Disk', "{$usedPercent}% used ({$freeGb} GB free)");
            }

            return $this->healthy('Disk', "{$usedPercent}% used ({$freeGb} GB free)");
        } catch (Throwable $e) {
            return $this->warning('Disk', $e->getMessage());
        }
    }

    public function checkWorkspace(): array
    {
        $root = config('git.workspace_root');

        if (!is_dir($root)) {
            return $this->warning('Workspace', "Workspace root does not exist: {$root}");
        }

        if (!is_writable($root)) {
            return $this->unhealthy('Workspace', "Workspace root is not writable: {$root}");
        }

        $count = count(glob($root . '/*', GLOB_ONLYDIR));
        return $this->healthy('Workspace', "{$count} workspace(s) present.");
    }

    // --- Helpers ---

    private function healthy(string $name, string $detail = '', array $meta = []): array
    {
        return [
            'name'   => $name,
            'status' => 'healthy',
            'detail' => $detail,
            'meta'   => $meta,
        ];
    }

    private function warning(string $name, string $detail = '', array $meta = []): array
    {
        return [
            'name'   => $name,
            'status' => 'warning',
            'detail' => $detail,
            'meta'   => $meta,
        ];
    }

    private function unhealthy(string $name, string $detail = '', array $meta = []): array
    {
        return [
            'name'   => $name,
            'status' => 'unhealthy',
            'detail' => $detail,
            'meta'   => $meta,
        ];
    }
}
