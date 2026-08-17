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
        $user = auth()->user();
        $firstWebsiteQuery = Website::query();
        if ($user) {
            $firstWebsiteQuery->where('user_id', $user->id);
        }
        $firstWebsite = $firstWebsiteQuery->first();
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
        $this->selectedWebsiteId = (int)$val;
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

    // Animated Workflow Execution Modal State
    public bool $showWorkflowModal = false;
    public ?int $activeRunningJobId = null;
    public array $workflowResult = [];

    public function runNow($jobId)
    {
        $this->activeRunningJobId = $jobId;
        $this->showWorkflowModal = true;
        
        $job = RewriteJob::find($jobId);
        if (!$job) {
            $this->workflowResult = [
                'success' => false,
                'failed_label' => 'Job Lookup',
                'error_message' => 'Job #' . $jobId . ' not found in database.',
                'steps' => [],
            ];
            return;
        }

        try {
            $executor = new \App\Services\JobExecutionService();
            $result = $executor->executeJobWithSteps($job);
            $this->workflowResult = $result;

            if ($result['success'] ?? false) {
                $this->dispatch('toast', title: 'Job Executed Successfully! 🚀', message: "Job #{$jobId} processed through AI & pushed to GitHub!", type: 'success');
            } else {
                $this->dispatch('toast', title: 'Workflow Step Failed ⚠️', message: "Failed at {$result['failed_label']}: {$result['error_message']}", type: 'danger');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("RunNow Workflow Execution Error: " . $e->getMessage());
            $this->workflowResult = [
                'success' => false,
                'failed_label' => 'System Execution',
                'error_message' => $e->getMessage(),
                'steps' => [],
            ];
            $this->dispatch('toast', title: 'System Exception', message: $e->getMessage(), type: 'danger');
        }
    }

    public function approveAndPush($jobId)
    {
        $job = RewriteJob::with(['website', 'page', 'result'])->find($jobId);
        if (!$job) {
            $this->dispatch('toast', title: 'Job Not Found', message: 'Selected job was not found.', type: 'danger');
            return;
        }

        try {
            $gitService = new \App\Services\GitService();
            $pagePath = $job->page?->path ?? '/index.html';
            
            // Get the AI-rewritten & style-preserved HTML from result
            $rewrittenHtml = $job->result?->rewritten_segments['html'] ?? null;

            if (empty($rewrittenHtml)) {
                // Fallback: If not stored, fetch from GitHub
                $githubApi = new \App\Services\GithubApiService();
                $fileData = $githubApi->getFileContent($job->website, $pagePath);
                $rewrittenHtml = $fileData['content'] ?? '';
            }
            
            $gitRes = $gitService->commitAndPush(
                $job->website,
                "Autoflow AI (Manual Approved): Refreshed {$pagePath}",
                $pagePath,
                $rewrittenHtml
            );

            if (is_array($gitRes) && isset($gitRes['success']) && !$gitRes['success']) {
                $this->dispatch('toast', title: 'GitHub Push Failed ⚠️', message: $gitRes['message'] ?? 'Could not push to GitHub.', type: 'danger');
                return;
            }

            $job->update([
                'status' => JobStatus::Completed,
                'validation_status' => \App\Enums\ValidationStatus::Passed,
                'finished_at' => now(),
            ]);

            $this->dispatch('toast', title: 'Approved & Pushed! 🚀', message: "Job #{$job->id} changes pushed to GitHub main branch and deployed to Vercel!", type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', title: 'Push Exception', message: $e->getMessage(), type: 'danger');
        }
    }

    public function closeWorkflowModal()
    {
        $this->showWorkflowModal = false;
        $this->workflowResult = [];
        $this->activeRunningJobId = null;
    }

    public function approveJob($jobId)
    {
        $job = RewriteJob::find($jobId);
        if ($job) {
            $job->update(['status' => JobStatus::Completed, 'finished_at' => now()]);
            $this->dispatch('toast', title: 'Job Approved', message: "Job #{$jobId} approved and committed successfully.", type: 'success');
        }
    }

    public function clearCompletedJobs()
    {
        $query = RewriteJob::whereIn('status', [JobStatus::Completed, JobStatus::Cancelled, JobStatus::Failed, 'completed', 'cancelled', 'failed']);
        if ($this->websiteFilter) {
            $query->where('website_id', $this->websiteFilter);
        }
        $count = $query->count();
        $query->delete();

        $this->dispatch('toast', title: 'Job History Cleared', message: "Deleted {$count} past completed/failed job log records.", type: 'info');
    }

    public function clearAllLogs()
    {
        $user = auth()->user();
        $isSuper = $user && $user->isSuperAdmin();

        $query = RewriteJob::whereIn('status', [JobStatus::Completed, JobStatus::Failed, JobStatus::Cancelled]);
        if (!$isSuper && $user) {
            $query->whereHas('website', fn($q) => $q->where('user_id', $user->id));
        }
        $count = $query->delete();
        $this->dispatch('toast', title: 'Logs Cleared', message: "Removed {$count} finished and failed job records.", type: 'info');
    }

    public function clearSingleLog($id)
    {
        RewriteJob::destroy($id);
        $this->dispatch('toast', title: 'Log Deleted', message: "Removed job #{$id} record.", type: 'info');
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
        $user = auth()->user();
        $isSuper = $user && $user->isSuperAdmin();

        $websitesQuery = Website::query();
        $modelsQuery = AiModel::query();
        $jobsQuery = RewriteJob::with(['website', 'page', 'aiModel'])->latest();

        if ($user) {
            $websitesQuery->where('user_id', $user->id);
            $jobsQuery->whereHas('website', fn($q) => $q->where('user_id', $user->id));
        }

        if (!$isSuper && $user) {
            $modelsQuery->whereHas('provider', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id')
                  ->orWhereHas('user', fn($sq) => $sq->where('role', 'superadmin'));
            });
        }

        $websites = $websitesQuery->withCount('rewriteJobs')->get();
        
        // If selectedWebsiteId is not set or not in websites list, take the first available
        if (!$this->selectedWebsiteId && $websites->isNotEmpty()) {
            $this->selectedWebsiteId = $websites->first()->id;
        }

        $availablePages = $this->selectedWebsiteId 
            ? WebsitePage::where('website_id', $this->selectedWebsiteId)->orderBy('path')->get() 
            : collect();
        $aiModels = $modelsQuery->get();

        if ($this->websiteFilter) {
            $jobsQuery->where('website_id', $this->websiteFilter);
        }

        if ($this->statusFilter !== 'all') {
            $jobsQuery->where('status', $this->statusFilter);
        }

        $allJobs = $jobsQuery->get();

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
