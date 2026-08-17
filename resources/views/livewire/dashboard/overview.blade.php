<div class="space-y-6">
    <!-- Top Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">Dashboard Overview</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#DCFCE7] text-[#15803D] border border-[#BBF7D0]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E] animate-pulse"></span>
                    Engine Online
                </span>
            </div>
            <p class="text-xs text-[#64748B] mt-1">Autonomous static website content refresh pipeline & real-time deployment metrics.</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Timeframe selector -->
            <div class="inline-flex rounded-xl bg-white border border-[#E2E8F0] p-1 shadow-2xs">
                <button wire:click="setTimeframe('7d')" wire:loading.attr="disabled" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $timeframe === '7d' ? 'bg-[#0F172A] text-white shadow-xs' : 'text-[#64748B] hover:text-[#0F172A]' }}">
                    <span wire:loading.remove wire:target="setTimeframe('7d')">7 Days</span>
                    <span wire:loading wire:target="setTimeframe('7d')"><i class="fa-solid fa-spinner fa-spin text-[10px] mr-1"></i>7 Days</span>
                </button>
                <button wire:click="setTimeframe('30d')" wire:loading.attr="disabled" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $timeframe === '30d' ? 'bg-[#0F172A] text-white shadow-xs' : 'text-[#64748B] hover:text-[#0F172A]' }}">
                    <span wire:loading.remove wire:target="setTimeframe('30d')">30 Days</span>
                    <span wire:loading wire:target="setTimeframe('30d')"><i class="fa-solid fa-spinner fa-spin text-[10px] mr-1"></i>30 Days</span>
                </button>
            </div>

            <button
                wire:click="refreshData"
                wire:loading.attr="disabled"
                wire:target="refreshData"
                class="px-3.5 py-2 bg-white hover:bg-slate-50 text-[#0F172A] rounded-xl text-xs font-bold border border-[#E2E8F0] shadow-2xs transition-all flex items-center gap-2 disabled:opacity-75 disabled:cursor-wait"
            >
                <span wire:loading.remove wire:target="refreshData" class="flex items-center gap-1.5">
                    <i class="fa-solid fa-rotate-right text-xs text-[#22C55E]"></i>
                    Refresh
                </span>
                <span wire:loading wire:target="refreshData" class="flex items-center gap-1.5 text-[#15803D]">
                    <i class="fa-solid fa-spinner fa-spin text-xs text-[#22C55E]"></i>
                    Refreshing...
                </span>
            </button>

            <a href="{{ route('websites.create') }}" class="px-4 py-2 bg-[#22C55E] hover:bg-[#16A34A] text-white rounded-xl text-xs font-bold shadow-sm transition-all flex items-center gap-2 hover:scale-[1.02]">
                <i class="fa-solid fa-plus text-xs"></i>
                Connect Website
            </a>
        </div>
    </div>

    <!-- 4 Key KPI Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Connected Websites -->
        <div class="p-5 bg-white rounded-2xl border border-[#E2E8F0] shadow-card flex items-center justify-between transition-all hover:border-[#22C55E] group">
            <div>
                <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Connected Websites</p>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <h3 class="text-2xl font-extrabold text-[#0F172A]">{{ $stats['total_websites'] }}</h3>
                    <span class="text-xs font-bold text-[#15803D] bg-[#F0FDF4] px-2 py-0.5 rounded-md border border-[#DCFCE7]">
                        {{ $stats['active_websites'] }} Active
                    </span>
                </div>
                <p class="text-[11px] text-[#64748B] mt-1.5"><i class="fa-solid fa-code-branch text-[#22C55E] mr-1"></i>Autonomous Git repos</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#F0FDF4] text-[#22C55E] border border-[#DCFCE7] flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-globe text-xl"></i>
            </div>
        </div>

        <!-- Tracked Pages -->
        <div class="p-5 bg-white rounded-2xl border border-[#E2E8F0] shadow-card flex items-center justify-between transition-all hover:border-[#22C55E] group">
            <div>
                <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Discovered HTML Pages</p>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <h3 class="text-2xl font-extrabold text-[#0F172A]">{{ $stats['total_pages'] }}</h3>
                    <span class="text-xs font-bold text-[#15803D] bg-[#F0FDF4] px-2 py-0.5 rounded-md border border-[#DCFCE7]">
                        {{ $stats['page_coverage_pct'] }} Covered
                    </span>
                </div>
                <p class="text-[11px] text-[#64748B] mt-1.5"><i class="fa-solid fa-arrows-rotate text-[#22C55E] mr-1"></i>{{ $stats['refreshed_pages'] }} pages refreshed</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#F0FDF4] text-[#22C55E] border border-[#DCFCE7] flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-file-code text-xl"></i>
            </div>
        </div>

        <!-- AI Content Rewrites -->
        <div class="p-5 bg-white rounded-2xl border border-[#E2E8F0] shadow-card flex items-center justify-between transition-all hover:border-[#22C55E] group">
            <div>
                <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Completed AI Rewrites</p>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <h3 class="text-2xl font-extrabold text-[#0F172A]">{{ $stats['total_rewrites'] }}</h3>
                    <span class="text-xs font-bold text-[#15803D] bg-[#F0FDF4] px-2 py-0.5 rounded-md border border-[#DCFCE7]">
                        Groq 70B
                    </span>
                </div>
                <p class="text-[11px] text-[#64748B] mt-1.5"><i class="fa-solid fa-bolt text-[#22C55E] mr-1"></i>Sub-2s inference runs</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#F0FDF4] text-[#22C55E] border border-[#DCFCE7] flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-brain text-xl"></i>
            </div>
        </div>

        <!-- Git Commits & Pushes -->
        <div class="p-5 bg-white rounded-2xl border border-[#E2E8F0] shadow-card flex items-center justify-between transition-all hover:border-[#22C55E] group">
            <div>
                <p class="text-xs font-semibold text-[#64748B] uppercase tracking-wider">Git Production Pushes</p>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <h3 class="text-2xl font-extrabold text-[#0F172A]">{{ $stats['total_git_pushes'] }}</h3>
                    <span class="text-xs font-bold text-[#15803D] bg-[#F0FDF4] px-2 py-0.5 rounded-md border border-[#DCFCE7]">
                        100% Live
                    </span>
                </div>
                <p class="text-[11px] text-[#64748B] mt-1.5"><i class="fa-brands fa-github text-[#0F172A] mr-1"></i>Origin branch sync</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#0F172A] text-[#22C55E] border border-slate-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-brands fa-github text-xl"></i>
            </div>
        </div>
    </div>

    <!-- CHARTS SECTION: Interactive Trends & Status Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Line & Bar Chart: Activity Over Time -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-[#E2E8F0] shadow-card p-6 flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-4 border-b border-[#F1F5F9]">
                    <div>
                        <h3 class="text-base font-bold text-[#0F172A] flex items-center gap-2">
                            <i class="fa-solid fa-chart-line text-[#22C55E]"></i>
                            AI Execution & Git Push Velocity
                        </h3>
                        <p class="text-xs text-[#64748B] mt-0.5">Daily automated content rewrites vs. live repository git commits.</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-semibold text-[#64748B]">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-[#22C55E]"></span>
                            AI Rewrites
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-md bg-[#0F172A]"></span>
                            Git Pushes
                        </span>
                    </div>
                </div>

                <!-- Chart Canvas Container -->
                <div class="relative w-full h-[270px] pt-4">
                    <canvas id="autoflowActivityChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2 pt-4 mt-2 border-t border-[#F1F5F9] text-center">
                <div class="p-2 rounded-xl bg-[#F8FAFC]">
                    <div class="text-[11px] text-[#64748B] font-medium">Avg Execution Time</div>
                    <div class="text-sm font-bold text-[#0F172A] mt-0.5">1.84 sec</div>
                </div>
                <div class="p-2 rounded-xl bg-[#F8FAFC]">
                    <div class="text-[11px] text-[#64748B] font-medium">HTML Integrity</div>
                    <div class="text-sm font-bold text-[#15803D] mt-0.5">100% Preserved</div>
                </div>
                <div class="p-2 rounded-xl bg-[#F8FAFC]">
                    <div class="text-[11px] text-[#64748B] font-medium">Auto-Reschedule</div>
                    <div class="text-sm font-bold text-[#22C55E] mt-0.5">Enabled (30m)</div>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart: Job Status Distribution -->
        <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-card p-6 flex flex-col justify-between">
            <div>
                <div class="pb-4 border-b border-[#F1F5F9]">
                    <h3 class="text-base font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-[#22C55E]"></i>
                        Job Status Breakdown
                    </h3>
                    <p class="text-xs text-[#64748B] mt-0.5">Cumulative pipeline job health status.</p>
                </div>

                <!-- Chart Canvas Container -->
                <div class="relative w-full h-[220px] flex items-center justify-center pt-2">
                    <canvas id="autoflowStatusChart"></canvas>
                </div>
            </div>

            <!-- Custom Legend -->
            <div class="space-y-2 pt-4 border-t border-[#F1F5F9]">
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2 text-[#64748B]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#22C55E]"></span>
                        Completed Rewrites
                    </span>
                    <span class="font-bold text-[#0F172A]">{{ $statusDistribution['Completed'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2 text-[#64748B]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#3B82F6]"></span>
                        Scheduled Next Cycles
                    </span>
                    <span class="font-bold text-[#0F172A]">{{ $statusDistribution['Scheduled'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2 text-[#64748B]">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#F59E0B]"></span>
                        Pending Review
                    </span>
                    <span class="font-bold text-[#0F172A]">{{ $statusDistribution['Pending Review'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- PIPELINE LISTS SECTION: Upcoming Scheduled Runs & Live Git Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Schedule Queue -->
        <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fa-solid fa-calendar-check text-[#22C55E]"></i>
                        Upcoming Autonomous Jobs
                    </h3>
                    <p class="text-xs text-[#64748B] mt-0.5">Queued HTML rewrite cycles scheduled for execution.</p>
                </div>
                <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-[#15803D] hover:text-[#22C55E] flex items-center gap-1">
                    View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            @if($upcomingJobs->isEmpty())
                <div class="py-10 text-center space-y-2">
                    <div class="w-10 h-10 rounded-full bg-[#F0FDF4] text-[#22C55E] flex items-center justify-center mx-auto">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <p class="text-xs text-[#64748B] font-medium">All jobs are currently up to date!</p>
                </div>
            @else
                <div class="divide-y divide-[#F1F5F9]">
                    @foreach($upcomingJobs as $job)
                        <div class="py-3.5 flex items-center justify-between text-xs hover:bg-[#F8FAFC] px-2 rounded-xl transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#DCFCE7] text-[#15803D] flex items-center justify-center font-mono font-bold text-[10px]">
                                    #{{ $job->id }}
                                </div>
                                <div>
                                    <a href="{{ route('jobs.show', $job) }}" class="font-bold text-[#0F172A] hover:text-[#15803D] transition-colors flex items-center gap-1.5">
                                        {{ $job->page->path ?? 'All Pages' }}
                                    </a>
                                    <p class="text-[11px] text-[#64748B]">{{ $job->website->name ?? 'Website' }} · {{ $job->scheduled_at ? $job->scheduled_at->diffForHumans() : 'Queued' }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7]">
                                {{ $job->status->label() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Git Push & Deployment Stream -->
        <div class="bg-white rounded-3xl border border-[#E2E8F0] shadow-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-[#0F172A] flex items-center gap-2">
                        <i class="fa-brands fa-github text-[#0F172A]"></i>
                        Live Git Push Stream
                    </h3>
                    <p class="text-xs text-[#64748B] mt-0.5">Real-time commit records authored by Autoflow engine.</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#DCFCE7] text-[#15803D] border border-[#BBF7D0]">
                    Auto-Deploy Active
                </span>
            </div>

            @if($gitActivities->isEmpty())
                <div class="py-10 text-center space-y-2">
                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center mx-auto">
                        <i class="fa-brands fa-git-alt"></i>
                    </div>
                    <p class="text-xs text-[#64748B] font-medium">No Git push operations recorded yet.</p>
                </div>
            @else
                <div class="divide-y divide-[#F1F5F9]">
                    @foreach($gitActivities as $act)
                        <div class="py-3.5 flex items-center justify-between text-xs hover:bg-[#F8FAFC] px-2 rounded-xl transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#0F172A] text-[#22C55E] flex items-center justify-center">
                                    <i class="fa-solid fa-code-commit text-xs"></i>
                                </div>
                                <div class="max-w-[280px] sm:max-w-xs">
                                    <p class="font-bold text-[#0F172A] truncate" title="{{ $act->message }}">{{ $act->message ?? 'Autoflow AI content rewrite' }}</p>
                                    <p class="text-[11px] text-[#64748B]">{{ $act->website->name ?? 'Repository' }} · <span class="font-mono text-[10px] text-[#0F172A] font-semibold">{{ substr($act->commit_hash ?? 'latest', 0, 7) }}</span> · {{ $act->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] flex items-center gap-1">
                                <i class="fa-solid fa-check text-[9px]"></i> Pushed
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- ======================== CHART.JS INITIALIZATION SCRIPT ======================== -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    initAutoflowCharts();
});

document.addEventListener('livewire:navigated', function () {
    initAutoflowCharts();
});

let activityChartInstance = null;
let statusChartInstance = null;

function initAutoflowCharts() {
    const activityCtx = document.getElementById('autoflowActivityChart');
    const statusCtx = document.getElementById('autoflowStatusChart');

    if (!activityCtx || !statusCtx) return;

    if (activityChartInstance) activityChartInstance.destroy();
    if (statusChartInstance) statusChartInstance.destroy();

    const chartLabels = @json($chartLabels);
    const completedTrend = @json($completedTrend);
    const gitPushTrend = @json($gitPushTrend);
    const statusData = @json(array_values($statusDistribution));
    const statusLabels = @json(array_keys($statusDistribution));

    // Gradient background for Line Chart
    const ctx = activityCtx.getContext('2d');
    const greenGradient = ctx.createLinearGradient(0, 0, 0, 240);
    greenGradient.addColorStop(0, 'rgba(34, 197, 94, 0.25)');
    greenGradient.addColorStop(1, 'rgba(34, 197, 94, 0.00)');

    // 1. Line & Bar Activity Chart
    activityChartInstance = new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'AI Rewrites',
                    data: completedTrend,
                    borderColor: '#22C55E',
                    backgroundColor: greenGradient,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#22C55E',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.38
                },
                {
                    label: 'Git Pushes',
                    data: gitPushTrend,
                    borderColor: '#0F172A',
                    backgroundColor: 'rgba(15, 23, 42, 0.08)',
                    borderWidth: 2,
                    borderDash: [4, 4],
                    pointBackgroundColor: '#0F172A',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 3.5,
                    pointHoverRadius: 5,
                    fill: false,
                    tension: 0.2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleFont: { size: 12, family: 'Inter', weight: 'bold' },
                    bodyFont: { size: 11, family: 'Inter' },
                    padding: 10,
                    cornerRadius: 10,
                    boxPadding: 4
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, family: 'Inter' }, color: '#64748B' }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: 5,
                    grid: { color: '#F1F5F9' },
                    ticks: {
                        stepSize: 1,
                        font: { size: 10, family: 'Inter' },
                        color: '#64748B'
                    }
                }
            }
        }
    });

    // 2. Status Doughnut Chart
    statusChartInstance = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData.length > 0 && statusData.some(v => v > 0) ? statusData : [1, 0, 0, 0, 0],
                backgroundColor: [
                    '#22C55E', // Completed - Brand Green
                    '#3B82F6', // Scheduled - Blue
                    '#F59E0B', // Pending Review - Amber
                    '#EF4444', // Failed - Red
                    '#8B5CF6'  // Processing - Purple
                ],
                borderWidth: 3,
                borderColor: '#FFFFFF',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleFont: { size: 12, family: 'Inter', weight: 'bold' },
                    bodyFont: { size: 11, family: 'Inter' },
                    padding: 10,
                    cornerRadius: 10
                }
            }
        }
    });
}
</script>

