<?php

namespace App\Livewire\Dashboard;

use App\Enums\JobStatus;
use App\Enums\WebsiteStatus;
use App\Models\GitOperation;
use App\Models\RewriteJob;
use App\Models\Website;
use App\Models\WebsitePage;
use Livewire\Component;

class Overview extends Component
{
    public string $timeframe = '30d';
    public bool $isRefreshing = false;

    public function setTimeframe(string $timeframe)
    {
        if (in_array($timeframe, ['7d', '30d', '90d'])) {
            $this->timeframe = $timeframe;
            $this->dispatch('toast', title: 'Timeframe Updated', message: "Dashboard data adjusted for past {$timeframe}.", type: 'info');
        }
    }

    public function refreshData()
    {
        $this->isRefreshing = true;
        $this->dispatch('toast', title: 'Dashboard Refreshed', message: 'Metrics updated successfully.', type: 'success');
        $this->isRefreshing = false;
    }

    public function render()
    {
        // Actual Database Metrics
        $totalWebsites = Website::count();
        $activeWebsites = Website::where('status', WebsiteStatus::Active)->count();

        $totalPages = WebsitePage::count();
        $refreshedPages = WebsitePage::whereNotNull('last_rewrite_at')->count();

        $completedJobsCount = RewriteJob::where('status', JobStatus::Completed)->count();
        $pendingJobsCount = RewriteJob::where('status', JobStatus::PendingApproval)->count();

        $pageCoveragePct = $totalPages > 0 ? round(($refreshedPages / $totalPages) * 100, 1) . '%' : '0%';

        $stats = [
            'total_websites' => $totalWebsites,
            'active_websites' => $activeWebsites,
            'total_pages' => $totalPages,
            'refreshed_pages' => $refreshedPages,
            'page_coverage_pct' => $pageCoveragePct,
            'total_rewrites' => $completedJobsCount,
            'pending_approvals' => $pendingJobsCount,
        ];

        // Real Upcoming Jobs
        $upcomingJobs = RewriteJob::with(['website', 'page'])
            ->whereIn('status', [JobStatus::Scheduled, JobStatus::Queued, JobStatus::PendingApproval])
            ->latest()
            ->take(5)
            ->get();

        // Real Activity Logs / Git Operations
        $gitActivities = GitOperation::with('website')->latest()->take(5)->get();

        return view('livewire.dashboard.overview', [
            'stats' => $stats,
            'upcomingJobs' => $upcomingJobs,
            'gitActivities' => $gitActivities,
        ]);
    }
}
