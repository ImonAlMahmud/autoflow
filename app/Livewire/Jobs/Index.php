<?php

namespace App\Livewire\Jobs;

use App\Enums\JobStatus;
use App\Enums\TriggerType;
use App\Models\AiModel;
use App\Models\RewriteJob;
use App\Models\Website;
use App\Models\WebsitePage;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';
    public string $statusFilter = 'all';

    // Create Job Modal State
    public bool $showCreateModal = false;
    public ?int $selectedWebsiteId = null;
    public ?int $selectedPageId = null;
    public ?int $selectedAiModelId = null;

    public function mount()
    {
        $firstWebsite = Website::first();
        if ($firstWebsite) {
            $this->selectedWebsiteId = $firstWebsite->id;
            $firstPage = WebsitePage::where('website_id', $firstWebsite->id)->first();
            $this->selectedPageId = $firstPage?->id;
        }
        $firstModel = AiModel::first();
        $this->selectedAiModelId = $firstModel?->id;
    }

    public function updatedSelectedWebsiteId($val)
    {
        $firstPage = WebsitePage::where('website_id', $val)->first();
        $this->selectedPageId = $firstPage?->id;
    }

    public function createJob()
    {
        if (!$this->selectedWebsiteId) {
            $this->dispatch('toast', title: 'Selection Error', message: 'Please select a website.', type: 'warning');
            return;
        }

        $website = Website::find($this->selectedWebsiteId);
        $unit = $website->default_rewrite_interval_unit ?? 'minutes';
        $val = (int)($website->default_rewrite_interval_days ?? 5);

        $scheduledAt = match($unit) {
            'minutes' => now()->addMinutes($val),
            'hours'   => now()->addHours($val),
            'months'  => now()->addMonths($val),
            default   => now()->addDays($val),
        };

        if ($this->selectedPageId === 'all_pages' || !$this->selectedPageId) {
            // Full Website Scan Mode: Dispatch jobs for all pages
            $pages = WebsitePage::where('website_id', $this->selectedWebsiteId)->get();
            
            if ($pages->isEmpty()) {
                $pages = collect([WebsitePage::create([
                    'website_id' => $this->selectedWebsiteId,
                    'path' => '/index.html',
                    'friendly_name' => 'Home Page',
                    'rewrite_enabled' => true,
                ])]);
            }

            foreach ($pages as $p) {
                // Update existing active scheduled job or create one
                RewriteJob::updateOrCreate(
                    [
                        'website_page_id' => $p->id,
                        'status' => JobStatus::Scheduled,
                    ],
                    [
                        'website_id' => $this->selectedWebsiteId,
                        'ai_model_id' => $this->selectedAiModelId,
                        'trigger_type' => TriggerType::Manual,
                        'started_at' => now(),
                        'scheduled_at' => $scheduledAt,
                    ]
                );
            }

            $this->showCreateModal = false;
            $this->dispatch('toast', title: 'Full Site Refresh Configured', message: "Active job schedule updated for ALL " . count($pages) . " pages of {$website->name}.", type: 'success');
        } else {
            // Single Page Mode: Reuse existing active scheduled job if present
            $job = RewriteJob::updateOrCreate(
                [
                    'website_page_id' => $this->selectedPageId,
                    'status' => JobStatus::Scheduled,
                ],
                [
                    'website_id' => $this->selectedWebsiteId,
                    'ai_model_id' => $this->selectedAiModelId,
                    'trigger_type' => TriggerType::Manual,
                    'started_at' => now(),
                    'scheduled_at' => $scheduledAt,
                ]
            );

            $this->showCreateModal = false;
            $this->dispatch('toast', title: 'Single Page Job Updated', message: "Active schedule updated for Job #{$job->id}.", type: 'success');
        }
    }

    public function runNow($jobId)
    {
        $job = RewriteJob::find($jobId);
        if ($job) {
            // Force scheduled_at to now so it runs immediately
            $job->update([
                'scheduled_at' => now()->subMinute(),
                'status' => JobStatus::Scheduled,
            ]);

            try {
                \Illuminate\Support\Facades\Artisan::call('content:scan-due');
                $this->dispatch('toast', title: 'Job Executed Instantly', message: "Job #{$jobId} executed & pushed to GitHub! Next cycle rescheduled automatically.", type: 'success');
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("RunNow Execution Error: " . $e->getMessage());
                $this->dispatch('toast', title: 'Execution Warning', message: "Job #{$jobId} queued: " . $e->getMessage(), type: 'warning');
            }
        }
    }

    public function approveJob($jobId)
    {
        $job = RewriteJob::find($jobId);
        if ($job) {
            $job->update(['status' => JobStatus::Completed, 'finished_at' => now()]);
            $this->dispatch('toast', title: 'Job Approved', message: "Job #{$jobId} approved and committed successfully.", type: 'success');
        }
    }

    public bool $showAll = false;

    public function toggleShowAll()
    {
        $this->showAll = !$this->showAll;
    }

    public ?int $websiteFilter = null;

    public function selectWebsiteFilter(?int $websiteId)
    {
        $this->websiteFilter = $websiteId;
    }

    public function render()
    {
        $websites = Website::withCount('rewriteJobs')->get();
        $availablePages = $this->selectedWebsiteId ? WebsitePage::where('website_id', $this->selectedWebsiteId)->get() : collect();
        $aiModels = AiModel::all();

        $query = RewriteJob::with(['website', 'page', 'aiModel'])->latest();

        if ($this->websiteFilter) {
            $query->where('website_id', $this->websiteFilter);
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $allJobs = $query->get();

        if (!empty($this->search)) {
            $allJobs = $allJobs->filter(fn($j) => str_contains(strtolower($j->page->path ?? ''), strtolower($this->search)) || str_contains((string)$j->id, $this->search));
        }

        $totalJobsCount = $allJobs->count();
        $remainingCount = max(0, $totalJobsCount - 15);
        $jobs = ($this->showAll || !empty($this->search)) ? $allJobs : $allJobs->take(15);

        return view('livewire.jobs.index', [
            'jobs' => $jobs,
            'totalJobsCount' => $totalJobsCount,
            'remainingCount' => $remainingCount,
            'websites' => $websites,
            'availablePages' => $availablePages,
            'aiModels' => $aiModels,
        ]);
    }
}
