<div class="space-y-6">
    <!-- Top Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#101828] tracking-tight">Dashboard Overview</h1>
            <p class="text-xs text-[#667085] mt-1">Welcome back! Manage and monitor your automated static website content refresh pipeline.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('websites.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                + Connect Website
            </a>
        </div>
    </div>

    <!-- 4 Main Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Website Card -->
        <div class="p-5 bg-white rounded-2xl border border-[#EAECF0] shadow-card flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-[#667085] uppercase tracking-wider">Connected Websites</p>
                <h3 class="text-2xl font-bold text-[#101828] mt-1.5">{{ $stats['total_websites'] }}</h3>
                <p class="text-[11px] text-[#667085] mt-1"><span class="font-medium text-emerald-600">{{ $stats['active_websites'] }} Active</span> repositories</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
            </div>
        </div>

        <!-- Tracked Pages Card -->
        <div class="p-5 bg-white rounded-2xl border border-[#EAECF0] shadow-card flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-[#667085] uppercase tracking-wider">Tracked Pages</p>
                <h3 class="text-2xl font-bold text-[#101828] mt-1.5">{{ $stats['total_pages'] }}</h3>
                <p class="text-[11px] text-[#667085] mt-1"><span class="font-medium text-indigo-600">{{ $stats['refreshed_pages'] }} Refreshed</span></p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
        </div>

        <!-- AI Rewrites Card -->
        <div class="p-5 bg-white rounded-2xl border border-[#EAECF0] shadow-card flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-[#667085] uppercase tracking-wider">Executed AI Rewrites</p>
                <h3 class="text-2xl font-bold text-[#101828] mt-1.5">{{ $stats['total_rewrites'] }}</h3>
                <p class="text-[11px] text-[#667085] mt-1">Completed automation runs</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </div>
        </div>

        <!-- Pending Approvals Card -->
        <div class="p-5 bg-white rounded-2xl border border-[#EAECF0] shadow-card flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-[#667085] uppercase tracking-wider">Pending Approvals</p>
                <h3 class="text-2xl font-bold text-[#101828] mt-1.5">{{ $stats['pending_approvals'] }}</h3>
                <p class="text-[11px] text-[#667085] mt-1">Requires manual review</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
    </div>

    <!-- Empty State Onboarding Section (When 0 websites added) -->
    @if($stats['total_websites'] == 0)
        <div class="p-8 bg-white rounded-2xl border border-[#EAECF0] shadow-card text-center space-y-4 max-w-2xl mx-auto my-6">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-[#101828]">No Websites Connected Yet</h3>
                <p class="text-xs text-[#667085] mt-1 max-w-md mx-auto">Connect your first static Git website repository to enable AI content refresh automation.</p>
            </div>
            <div>
                <a href="{{ route('websites.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                    + Connect New Website
                </a>
            </div>
        </div>
    @else
        <!-- Real Lists Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Upcoming Schedule List -->
            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-card p-5">
                <h3 class="text-sm font-bold text-[#101828] mb-3">Upcoming Scheduled Jobs</h3>
                @if($upcomingJobs->isEmpty())
                    <p class="text-xs text-[#98A2B3] py-4 text-center">No scheduled jobs pending.</p>
                @else
                    <div class="divide-y divide-[#EAECF0]">
                        @foreach($upcomingJobs as $job)
                            <div class="py-3 flex items-center justify-between text-xs">
                                <div>
                                    <p class="font-semibold text-[#101828]">{{ $job->page->path ?? 'Page' }}</p>
                                    <p class="text-[#667085]">{{ $job->website->name ?? '' }}</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $job->status->label() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Git Activity -->
            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-card p-5">
                <h3 class="text-sm font-bold text-[#101828] mb-3">Recent Git Activity</h3>
                @if($gitActivities->isEmpty())
                    <p class="text-xs text-[#98A2B3] py-4 text-center">No Git activity recorded yet.</p>
                @else
                    <div class="divide-y divide-[#EAECF0]">
                        @foreach($gitActivities as $act)
                            <div class="py-3 flex items-center justify-between text-xs">
                                <div>
                                    <p class="font-semibold text-[#101828]">{{ $act->message ?? 'Git update' }}</p>
                                    <p class="text-[#667085]">{{ $act->website->name ?? '' }} • {{ $act->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    {{ strtoupper($act->operation) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
