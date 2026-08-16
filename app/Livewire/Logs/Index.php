<?php

namespace App\Livewire\Logs;

use Livewire\Component;

class Index extends Component
{
    public string $search = '';
    public string $levelFilter = 'all';

    public function exportLogs()
    {
        $this->dispatch('toast', title: 'Export Started', message: 'Downloading system diagnostic JSON logs.', type: 'info');
    }

    public function clearLogs()
    {
        $this->dispatch('toast', title: 'Logs Cleared', message: 'Audit history cleared successfully.', type: 'warning');
    }

    public function render()
    {
        $logs = collect([
            (object)[
                'id' => 1,
                'created_at' => now()->subMinutes(5),
                'level' => 'info',
                'module' => 'GitSyncWorker',
                'message' => 'Successfully pushed commit #a9f82c4 to origin/main for website techcorp.io',
                'context' => '{"repo": "techcorp/docs.git", "branch": "main", "status": "200 OK"}',
            ],
            (object)[
                'id' => 2,
                'created_at' => now()->subMinutes(18),
                'level' => 'info',
                'module' => 'AIGenerateJob',
                'message' => 'AI rewrite completed for page /products/cloud-platform using model gpt-4o',
                'context' => '{"prompt_tokens": 1240, "completion_tokens": 850, "latency_ms": 3420}',
            ],
            (object)[
                'id' => 3,
                'created_at' => now()->subMinutes(42),
                'level' => 'warning',
                'module' => 'HTMLValidator',
                'message' => 'Detected unclosed <div> tag in raw extraction; auto-repaired before AI prompt injection',
                'context' => '{"page_id": 102, "repair_action": "auto_close_tags"}',
            ],
            (object)[
                'id' => 4,
                'created_at' => now()->subHours(1),
                'level' => 'info',
                'module' => 'ScheduleWorker',
                'message' => 'Content refresh cron tick evaluated 14 websites; 3 pages due for rewrite',
                'context' => '{"evaluated_pages": 788, "queued_jobs": 3}',
            ],
        ]);

        if (!empty($this->search)) {
            $logs = $logs->filter(fn($l) => str_contains(strtolower($l->message), strtolower($this->search)) || str_contains(strtolower($l->module), strtolower($this->search)));
        }

        if ($this->levelFilter !== 'all') {
            $logs = $logs->filter(fn($l) => $l->level === $this->levelFilter);
        }

        return view('livewire.logs.index', [
            'logs' => $logs,
        ]);
    }
}
