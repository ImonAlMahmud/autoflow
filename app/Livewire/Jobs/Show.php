<?php

namespace App\Livewire\Jobs;

use App\Enums\JobStatus;
use App\Models\RewriteJob;
use Livewire\Component;

class Show extends Component
{
    public $jobId;
    public $job;
    public string $reviewerNotes = '';

    public function mount($job = null)
    {
        $this->jobId = is_object($job) ? $job->id : $job;
        if ($this->jobId) {
            $this->job = RewriteJob::with(['website', 'page', 'aiModel', 'result'])->find($this->jobId);
            if ($this->job && !$this->job->scheduled_at) {
                $unit = $this->job->website->default_rewrite_interval_unit ?? 'minutes';
                $val = (int)($this->job->website->default_rewrite_interval_days ?? 5);
                $createdAt = $this->job->created_at ? $this->job->created_at->clone() : now();
                $scheduledAt = match($unit) {
                    'minutes' => $createdAt->addMinutes($val),
                    'hours'   => $createdAt->addHours($val),
                    'months'  => $createdAt->addMonths($val),
                    default   => $createdAt->addDays($val),
                };
                $this->job->update(['scheduled_at' => $scheduledAt]);
            }
        }
    }

    public function discardJob()
    {
        if ($this->job && is_a($this->job, RewriteJob::class)) {
            $this->job->update(['status' => JobStatus::Cancelled]);
        }

        $this->dispatch('toast', title: 'Job Discarded', message: 'The candidate rewrite was rejected and archived.', type: 'warning');
        return redirect()->route('jobs.index');
    }

    public function approveAndPush()
    {
        if ($this->job && is_a($this->job, RewriteJob::class)) {
            // Execute real Git commit & push if website exists
            if ($this->job->website) {
                $gitService = new \App\Services\GitService();
                $result = $gitService->commitAndPush($this->job->website, "Autoflow AI: Refreshed {$this->job->page?->path}");
            }

            $this->job->update([
                'status' => JobStatus::Completed,
                'finished_at' => now(),
                'reviewer_notes' => $this->reviewerNotes ?: 'Approved by user.',
            ]);
        }

        $this->dispatch('toast', title: 'Changes Approved & Committed', message: 'Content committed to local repository and pushed to Git remote branch.', type: 'success');
        return redirect()->route('jobs.index');
    }

    public function render()
    {
        return view('livewire.jobs.show', [
            'job' => $this->job,
        ]);
    }
}
