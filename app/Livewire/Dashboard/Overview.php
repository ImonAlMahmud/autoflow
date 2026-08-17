<?php

namespace App\Livewire\Dashboard;

use App\Enums\JobStatus;
use App\Enums\WebsiteStatus;
use App\Models\GitOperation;
use App\Models\RewriteJob;
use App\Models\Website;
use App\Models\WebsitePage;
use Carbon\Carbon;
use Livewire\Component;

class Overview extends Component
{
    public string $timeframe = '7d';
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
        $user = auth()->user();
        $isSuper = $user && $user->isSuperAdmin();

        // Actual Database Metrics
        $totalWebsites = $isSuper ? Website::count() : Website::where('user_id', $user?->id)->count();
        $activeWebsites = $isSuper ? Website::where('status', WebsiteStatus::Active)->count() : Website::where('user_id', $user?->id)->where('status', WebsiteStatus::Active)->count();

        $pagesQuery = $isSuper ? WebsitePage::query() : WebsitePage::whereHas('website', fn($q) => $q->where('user_id', $user?->id));
        $totalPages = $pagesQuery->count();
        $refreshedPages = (clone $pagesQuery)->whereNotNull('last_rewrite_at')->count();

        $jobsQuery = $isSuper ? RewriteJob::query() : RewriteJob::whereHas('website', fn($q) => $q->where('user_id', $user?->id));
        $completedJobsCount = (clone $jobsQuery)->where('status', JobStatus::Completed)->count();
        $failedJobsCount = (clone $jobsQuery)->where('status', JobStatus::Failed)->count();
        $pendingJobsCount = (clone $jobsQuery)->where('status', JobStatus::PendingApproval)->count();
        $scheduledJobsCount = (clone $jobsQuery)->where('status', JobStatus::Scheduled)->count();
        $processingJobsCount = (clone $jobsQuery)->where('status', JobStatus::AiProcessing)->count();

        $pageCoveragePct = $totalPages > 0 ? round(($refreshedPages / $totalPages) * 100, 1) : 0;

        // 7-day or 30-day rewrite activity trend
        $daysCount = $this->timeframe === '30d' ? 30 : ($this->timeframe === '90d' ? 90 : 7);
        $chartLabels = [];
        $completedTrend = [];
        $gitPushTrend = [];

        for ($i = $daysCount - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $chartLabels[] = $daysCount <= 7 ? $date->format('D, M j') : $date->format('M j');

            $completedCount = RewriteJob::where('status', JobStatus::Completed)
                ->whereDate('updated_at', $dateStr)
                ->count();
            
            $gitCount = GitOperation::whereDate('created_at', $dateStr)
                ->count();

            $completedTrend[] = $completedCount;
            $gitPushTrend[] = $gitCount;
        }

        // Job Status Distribution for Doughnut Chart
        $statusDistribution = [
            'Completed' => $completedJobsCount,
            'Scheduled' => $scheduledJobsCount,
            'Pending Review' => $pendingJobsCount,
            'Failed' => $failedJobsCount,
            'Processing' => $processingJobsCount,
        ];

        // Total Git Operations
        $totalGitPushes = GitOperation::count();

        $stats = [
            'total_websites' => $totalWebsites,
            'active_websites' => $activeWebsites,
            'total_pages' => $totalPages,
            'refreshed_pages' => $refreshedPages,
            'page_coverage_pct' => $pageCoveragePct . '%',
            'total_rewrites' => $completedJobsCount,
            'failed_rewrites' => $failedJobsCount,
            'pending_approvals' => $pendingJobsCount,
            'total_git_pushes' => $totalGitPushes,
        ];

        // Real Upcoming Jobs
        $upcomingJobs = RewriteJob::with(['website', 'page'])
            ->whereIn('status', [JobStatus::Scheduled, JobStatus::Queued, JobStatus::PendingApproval])
            ->latest()
            ->take(6)
            ->get();

        // Real Activity Logs / Git Operations
        $gitActivities = GitOperation::with('website')->latest()->take(6)->get();

        return view('livewire.dashboard.overview', [
            'stats' => $stats,
            'upcomingJobs' => $upcomingJobs,
            'gitActivities' => $gitActivities,
            'chartLabels' => $chartLabels,
            'completedTrend' => $completedTrend,
            'gitPushTrend' => $gitPushTrend,
            'statusDistribution' => $statusDistribution,
        ]);
    }
}

