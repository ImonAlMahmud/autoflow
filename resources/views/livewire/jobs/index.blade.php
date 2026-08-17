<div class="space-y-6" x-data="{ showCreateModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">Automation & Rewrite Jobs</h1>
            <p class="text-xs text-[#64748B] mt-1">Live queue of background content generation, validation, and Git commit tasks</p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Clear History Action Dropdown -->
            <div x-data="{ clearOpen: false }" class="relative">
                <button
                    @click="clearOpen = !clearOpen"
                    @click.away="clearOpen = false"
                    type="button"
                    class="px-3.5 py-2.5 rounded-xl border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#334155] font-semibold text-xs shadow-xs transition-all flex items-center gap-1.5"
                    title="Clear past executed job logs"
                >
                    <i class="fa-solid fa-trash text-rose-500 text-xs"></i>
                    <span>Clear Logs</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-[#94A3B8]"></i>
                </button>

                <div
                    x-show="clearOpen"
                    x-cloak
                    class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-[#E2E8F0] py-1.5 z-30 space-y-1"
                >
                    <button
                        wire:click="clearCompletedJobs"
                        @click="clearOpen = false"
                        type="button"
                        class="w-full text-left px-3.5 py-2 text-xs text-[#334155] hover:bg-[#F8FAFC] flex items-center gap-2 transition-colors font-medium"
                    >
                        <i class="fa-solid fa-broom text-amber-500 text-xs"></i>
                        <span>Clear Completed / Failed Logs</span>
                    </button>
                    <button
                        wire:click="clearAllJobs"
                        wire:confirm="Are you sure you want to completely clear all job logs? This cannot be undone."
                        @click="clearOpen = false"
                        type="button"
                        class="w-full text-left px-3.5 py-2 text-xs text-rose-600 hover:bg-rose-50 flex items-center gap-2 transition-colors font-semibold"
                    >
                        <i class="fa-solid fa-trash-can text-rose-600 text-xs"></i>
                        <span>Clear ALL Jobs (Full Reset)</span>
                    </button>
                </div>
            </div>

            <!-- + Dispatch New Job Button -->
            @if(auth()->user()?->hasActiveSubscription())
                <button
                    @click="showCreateModal = true"
                    type="button"
                    class="px-5 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs shadow-sm transition-all hover:scale-105 flex items-center gap-2"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    + Run / Dispatch New Job
                </button>
            @else
                <button
                    @click="$dispatch('open-paywall', { feature: 'AI Content Generation & Automation Jobs' })"
                    type="button"
                    class="px-5 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs shadow-sm transition-all hover:scale-105 flex items-center gap-2"
                >
                    <i class="fa-solid fa-lock text-xs"></i>
                    + Run / Dispatch New Job
                </button>
            @endif
        </div>
    </div>

    <!-- SECTION 1: WEBSITE FILTER TABS (FOR MANAGING 40-50+ SITES EASILY) -->
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-[#334155] uppercase tracking-wider flex items-center gap-1.5">
                <i class="fa-solid fa-filter text-[#22C55E] text-xs"></i>
                Filter Jobs By Website ({{ $websites->count() }} Managed Sites)
            </span>
            @if($websiteFilter)
                <button wire:click="selectWebsiteFilter(null)" type="button" class="text-xs font-semibold text-[#15803D] hover:text-[#166534] flex items-center gap-1">
                    Clear Site Filter ✕
                </button>
            @endif
        </div>

        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-thin">
            <!-- All Websites Tab -->
            <button
                wire:click="selectWebsiteFilter(null)"
                type="button"
                class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap shadow-xs {{ is_null($websiteFilter) ? 'bg-[#22C55E] text-white ring-2 ring-[#22C55E]/30' : 'bg-white text-[#475569] border border-[#E2E8F0] hover:bg-[#F8FAFC]' }}"
            >
                <span>🌐 All Websites</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ is_null($websiteFilter) ? 'bg-[#15803D] text-white' : 'bg-[#F1F5F9] text-[#64748B]' }}">
                    {{ $totalJobsCount }}
                </span>
            </button>

            <!-- Individual Website Tabs -->
            @foreach($websites as $site)
                <button
                    wire:click="selectWebsiteFilter({{ $site->id }})"
                    type="button"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap shadow-xs {{ $websiteFilter === $site->id ? 'bg-[#22C55E] text-white ring-2 ring-[#22C55E]/30' : 'bg-white text-[#475569] border border-[#E2E8F0] hover:bg-[#F8FAFC]' }}"
                >
                    <span class="truncate max-w-[180px]">{{ $site->name }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $websiteFilter === $site->id ? 'bg-[#15803D] text-white' : 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7]' }}">
                        {{ $site->rewrite_jobs_count }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Filter & Status Bar -->
    <div class="bg-white p-4 rounded-2xl border border-[#EAECF0] shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <!-- Search Input -->
        <div class="relative flex-1 max-w-md">
            <i class="fa-solid fa-magnifying-glass text-xs"></i>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search jobs by path or job ID..."
                class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-[#22C55E] transition-all"
            >
        </div>

        <!-- Filter Status Pills -->
        <div class="flex items-center gap-1 p-1 bg-[#F2F4F7] rounded-xl border border-[#EAECF0] text-xs">
            <button
                wire:click="$set('statusFilter', 'all')"
                class="px-3 py-1.5 font-medium rounded-lg transition-all {{ $statusFilter === 'all' ? 'bg-white text-[#15803D] shadow-xs font-semibold' : 'text-[#667085]' }}"
            >
                All Jobs
            </button>
            <button
                wire:click="$set('statusFilter', 'pending_approval')"
                class="px-3 py-1.5 font-medium rounded-lg transition-all {{ $statusFilter === 'pending_approval' ? 'bg-white text-amber-700 shadow-xs font-semibold' : 'text-[#667085]' }}"
            >
                Pending Review
            </button>
            <button
                wire:click="$set('statusFilter', 'completed')"
                class="px-3 py-1.5 font-medium rounded-lg transition-all {{ $statusFilter === 'completed' ? 'bg-white text-emerald-700 shadow-xs font-semibold' : 'text-[#667085]' }}"
            >
                Completed
            </button>
        </div>
    </div>

    <!-- Jobs Table Card -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#EAECF0] text-[#667085] font-semibold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4">Job ID</th>
                        <th class="py-3 px-4">Target Page & Website</th>
                        <th class="py-3 px-4">Trigger & Model</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Started / Dispatched</th>
                        <th class="py-3 px-4">Next Due / Schedule</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAECF0]">
                    @forelse($jobs as $job)
                        <tr class="hover:bg-[#F9FAFB]/80 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-[11px] font-semibold text-[#101828]">
                                #{{ $job->id }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-mono font-medium text-[#101828] block">{{ $job->page->path ?? '/index.html' }}</span>
                                <span class="text-[11px] text-[#667085]">{{ $job->website->name ?? 'Website' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-[#101828] block">{{ is_object($job->trigger_type) ? $job->trigger_type->label() : ($job->trigger_type ?? 'Manual') }}</span>
                                <span class="text-[11px] text-purple-700 font-medium">{{ $job->aiModel->name ?? 'Default AI' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-[#15803D] border border-emerald-200">
                                    {{ is_object($job->status) ? $job->status->label() : strtoupper($job->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#64748B]">
                                @php
                                    $statusVal = is_object($job->status) ? $job->status->value : (string)$job->status;
                                @endphp
                                @if($statusVal === 'completed')
                                    <div class="font-medium text-[#15803D] flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-xs"></i>
                                        Finished {{ $job->completed_at ? \Carbon\Carbon::parse($job->completed_at)->timezone('Asia/Dhaka')->diffForHumans() : ($job->updated_at ? \Carbon\Carbon::parse($job->updated_at)->timezone('Asia/Dhaka')->diffForHumans() : 'Recently') }}
                                    </div>
                                    <div class="text-[10px] text-[#64748B] font-mono">
                                        {{ $job->completed_at ? \Carbon\Carbon::parse($job->completed_at)->timezone('Asia/Dhaka')->format('h:i:s A') : ($job->updated_at ? \Carbon\Carbon::parse($job->updated_at)->timezone('Asia/Dhaka')->format('h:i:s A') : '') }}
                                    </div>
                                @elseif($statusVal === 'failed')
                                    <div class="font-medium text-rose-600">Failed {{ $job->updated_at ? \Carbon\Carbon::parse($job->updated_at)->timezone('Asia/Dhaka')->diffForHumans() : '' }}</div>
                                @else
                                    <div>Created {{ $job->created_at ? \Carbon\Carbon::parse($job->created_at)->timezone('Asia/Dhaka')->diffForHumans() : 'Just now' }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($statusVal === 'completed')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] inline-flex items-center gap-1">
                                        <i class="fa-brands fa-github text-xs"></i> Pushed (Live)
                                    </span>
                                @elseif($statusVal === 'failed')
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-triangle-exclamation text-xs"></i> Push Error
                                    </span>
                                @elseif(in_array($statusVal, ['cancelled', 'skipped']))
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-gray-50 text-gray-600 border border-gray-200 inline-flex items-center gap-1">
                                        {{ ucfirst($statusVal) }}
                                    </span>
                                @else
                                    @php
                                        $unit = $job->website->default_rewrite_interval_unit ?? 'minutes';
                                        $val = (int)($job->website->default_rewrite_interval_days ?? 5);
                                        $dueTime = $job->scheduled_at ? \Carbon\Carbon::parse($job->scheduled_at)->timezone('Asia/Dhaka') : \Carbon\Carbon::parse($job->created_at ?? now())->timezone('Asia/Dhaka')->addMinutes($val);
                                    @endphp
                                    <span
                                        x-data="{
                                            targetTime: {{ $dueTime->timestamp * 1000 }},
                                            timerText: '',
                                            updateTimer() {
                                                const now = new Date().getTime();
                                                const diff = this.targetTime - now;
                                                if (diff <= 0) {
                                                    this.timerText = 'Due Now (Running...)';
                                                    return;
                                                }
                                                const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                                const secs = Math.floor((diff % (1000 * 60)) / 1000);
                                                this.timerText = `${mins}m ${secs < 10 ? '0' : ''}${secs}s remaining`;
                                            }
                                        }"
                                        x-init="updateTimer(); setInterval(() => updateTimer(), 1000)"
                                        class="px-2.5 py-1 rounded-lg text-xs font-mono font-semibold bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1.5 shadow-xs"
                                    >
                                        <i class="fa-solid fa-clock text-xs"></i>
                                        <span x-text="timerText"></span>
                                        <span class="text-[10px] text-blue-500 font-sans">({{ $dueTime->format('h:i A') }})</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right flex items-center justify-end gap-2">
                                @if($statusVal === 'pending_approval')
                                     <button
                                         type="button"
                                         wire:click.prevent="approveAndPush({{ $job->id }})"
                                         wire:loading.attr="disabled"
                                         wire:target="approveAndPush({{ $job->id }})"
                                         title="Approve changes and push to GitHub repository"
                                         class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 disabled:bg-[#22C55E] disabled:opacity-90 disabled:cursor-wait text-white font-semibold text-xs transition-all inline-flex items-center gap-1.5 shadow-xs"
                                     >
                                         <span wire:loading.remove wire:target="approveAndPush({{ $job->id }})" class="inline-flex items-center gap-1">
                                             <i class="fa-brands fa-github text-xs"></i>
                                             Approve & Push
                                         </span>
                                         <span wire:loading wire:target="approveAndPush({{ $job->id }})" class="inline-flex items-center gap-1.5">
                                             <svg class="w-3.5 h-3.5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                                 <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                 <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                             </svg>
                                             <span>Pushing to Git...</span>
                                         </span>
                                     </button>
                                @elseif(!in_array($statusVal, ['completed', 'cancelled', 'failed']))
                                     <button
                                         type="button"
                                         wire:click.prevent="runNow({{ $job->id }})"
                                         wire:loading.attr="disabled"
                                         wire:target="runNow({{ $job->id }})"
                                         title="Run & Execute Job Instantly Now"
                                         class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 disabled:bg-[#22C55E] disabled:opacity-90 disabled:cursor-wait text-white font-semibold text-xs transition-all inline-flex items-center gap-1.5 shadow-xs"
                                     >
                                         <!-- Default State -->
                                         <span wire:loading.remove wire:target="runNow({{ $job->id }})" class="inline-flex items-center gap-1">
                                             <i class="fa-solid fa-circle-play text-xs"></i>
                                             Run Now
                                         </span>

                                         <!-- Spinner Loading State -->
                                         <span wire:loading wire:target="runNow({{ $job->id }})" class="inline-flex items-center gap-1.5">
                                             <svg class="w-3.5 h-3.5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                                 <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                 <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                             </svg>
                                             <span>Running AI...</span>
                                         </span>
                                     </button>
                                @endif
                                <a
                                    href="{{ route('jobs.show', $job->id) }}"
                                    class="px-3 py-1.5 rounded-lg border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#15803D] hover:border-[#22C55E] font-bold text-xs transition-all shadow-xs inline-flex items-center gap-1"
                                >
                                    <span>Details</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-4 text-center">
                                <div class="max-w-md mx-auto space-y-3">
                                    <p class="text-xs text-[#667085]">No automation jobs dispatched yet.</p>
                                    <button @click="showCreateModal = true" type="button" class="px-4 py-2 bg-[#22C55E] hover:bg-[#16A34A] text-white font-semibold text-xs rounded-xl shadow-xs transition-all">
                                        + Dispatch First AI Job
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($remainingCount > 0 || $showAll)
            <div class="px-4 py-3 bg-[#F9FAFB] border-t border-[#EAECF0] flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-[#667085]">
                <div class="flex items-center gap-2 font-medium">
                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-emerald-50 text-[#15803D] font-bold text-[11px] border border-emerald-200">
                        Showing {{ $jobs->count() }} of {{ $totalJobsCount }}
                    </span>
                    @if(!$showAll && $remainingCount > 0)
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 font-semibold text-[11px]">
                            +{{ $remainingCount }} More Jobs in History
                        </span>
                    @endif
                </div>
                <button
                    wire:click="toggleShowAll"
                    type="button"
                    class="px-3.5 py-1.5 rounded-xl bg-white border border-[#D0D5DD] hover:bg-slate-50 text-[#344054] font-semibold text-xs transition-all shadow-xs flex items-center gap-1.5 self-start sm:self-auto"
                >
                    @if($showAll)
                        <span>← Collapse to Top 15</span>
                    @else
                        <span>View All {{ $totalJobsCount }} Jobs →</span>
                    @endif
                </button>
            </div>
        @endif
    </div>

    <!-- Dispatch Job Modal -->
    <div x-show="showCreateModal" @close-modals.window="showCreateModal = false" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4" @click.self="showCreateModal = false">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl" @click.away="showCreateModal = false">
            <h3 class="text-base font-bold text-[#101828]">Dispatch New AI Rewrite Job</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Select Website *</label>
                    <select wire:model.live="selectedWebsiteId" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white">
                        @foreach($websites as $w)
                            <option value="{{ $w->id }}">{{ $w->name }} ({{ $w->domain }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Target Page or Scope *</label>
                    <select wire:model="selectedPageId" class="w-full px-3 py-2 text-xs font-medium rounded-xl border border-[#D0D5DD] bg-white text-[#101828]">
                        <option value="all_pages">🌐 Entire Website (All Pages Concurrent Scan)</option>
                        @foreach($availablePages as $p)
                            <option value="{{ $p->id }}">📄 {{ $p->path }} ({{ $p->friendly_name ?? 'Page' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">AI Model Engine</label>
                    <select wire:model="selectedAiModelId" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white">
                        @foreach($aiModels as $m)
                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->model_id }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button @click="showCreateModal = false" type="button" class="px-4 py-2 text-xs font-semibold text-[#667085] hover:bg-[#F9FAFB] rounded-xl">Cancel</button>
                <button wire:click="createJob" @click="showCreateModal = false" type="button" class="px-4 py-2 text-xs font-semibold text-white bg-[#22C55E] hover:bg-[#16A34A] rounded-xl">Run AI Job Now</button>
            </div>
        </div>
    </div>

    <!-- Animated Live Workflow Execution & Failure Inspector Modal -->
    @if($showWorkflowModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4 animate-in fade-in duration-200">
            <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-gray-100">
                
                <!-- Modal Top Header -->
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl {{ ($workflowResult['success'] ?? false) ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : (!empty($workflowResult) ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-emerald-50 text-[#15803D] border border-emerald-200 animate-pulse') }} flex items-center justify-center">
                            @if($workflowResult['success'] ?? false)
                                <i class="fa-solid fa-circle-check text-lg"></i>
                            @elseif(!empty($workflowResult))
                                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                            @else
                                <i class="fa-solid fa-gears text-lg fa-spin"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900 tracking-tight">
                                Job #{{ $activeRunningJobId }} — Live Pipeline Lifecycle
                            </h3>
                            <p class="text-xs text-gray-500">Real-time Step-by-Step AI Content Execution & Audit</p>
                        </div>
                    </div>

                    <button wire:click="closeWorkflowModal" type="button" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-50 transition-colors">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Workflow Execution Stepper / Progress -->
                <div class="space-y-4">
                    @php
                        $stepItems = $workflowResult['steps'] ?? [
                            'extract' => ['status' => 'pending', 'label' => '1. Extract HTML & Content Structure'],
                            'ai_rewrite' => ['status' => 'pending', 'label' => '2. Groq Llama 3.3 AI Rewrite'],
                            'patch_html' => ['status' => 'pending', 'label' => '3. Preserve Styles & Patch HTML'],
                            'git_sync' => ['status' => 'pending', 'label' => '4. Git Commit & Push to GitHub'],
                        ];
                    @endphp

                    <div class="space-y-3">
                        <!-- Step 1: Extract -->
                        <div class="p-3.5 rounded-2xl border transition-all flex items-start justify-between gap-3 {{ ($stepItems['extract']['status'] ?? '') === 'success' ? 'bg-emerald-50/50 border-emerald-200' : (($stepItems['extract']['status'] ?? '') === 'failed' ? 'bg-rose-50 border-rose-200' : 'bg-gray-50 border-gray-200') }}">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-xl flex items-center justify-center font-bold text-xs {{ ($stepItems['extract']['status'] ?? '') === 'success' ? 'bg-emerald-100 text-emerald-700' : (($stepItems['extract']['status'] ?? '') === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-gray-200 text-gray-600') }}">
                                    @if(($stepItems['extract']['status'] ?? '') === 'success')
                                        <i class="fa-solid fa-check"></i>
                                    @elseif(($stepItems['extract']['status'] ?? '') === 'failed')
                                        <i class="fa-solid fa-xmark"></i>
                                    @else
                                        1
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">1. Extract HTML Content & Structural Layout</h4>
                                    <p class="text-[11px] text-gray-500">{{ $stepItems['extract']['details'] ?? 'Scans target HTML, isolates content without touching CSS' }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-mono font-semibold {{ ($stepItems['extract']['status'] ?? '') === 'success' ? 'text-emerald-700' : 'text-gray-400' }}">
                                {{ $stepItems['extract']['time'] ?? 'Ready' }}
                            </span>
                        </div>

                        <!-- Step 2: Groq AI Generation -->
                        <div class="p-3.5 rounded-2xl border transition-all flex items-start justify-between gap-3 {{ ($stepItems['ai_rewrite']['status'] ?? '') === 'success' ? 'bg-emerald-50/50 border-emerald-200' : (($stepItems['ai_rewrite']['status'] ?? '') === 'failed' ? 'bg-rose-50 border-rose-200 ring-2 ring-rose-500/20' : 'bg-gray-50 border-gray-200') }}">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-xl flex items-center justify-center font-bold text-xs {{ ($stepItems['ai_rewrite']['status'] ?? '') === 'success' ? 'bg-emerald-100 text-emerald-700' : (($stepItems['ai_rewrite']['status'] ?? '') === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-gray-200 text-gray-600') }}">
                                    @if(($stepItems['ai_rewrite']['status'] ?? '') === 'success')
                                        <i class="fa-solid fa-check"></i>
                                    @elseif(($stepItems['ai_rewrite']['status'] ?? '') === 'failed')
                                        <i class="fa-solid fa-xmark"></i>
                                    @else
                                        2
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">2. Groq Llama 3.3 AI Humanized Rewrite</h4>
                                    <p class="text-[11px] text-gray-500">{{ $stepItems['ai_rewrite']['details'] ?? 'High-speed NLP inference, length matching & SEO enrichment' }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-mono font-semibold {{ ($stepItems['ai_rewrite']['status'] ?? '') === 'success' ? 'text-emerald-700' : (($stepItems['ai_rewrite']['status'] ?? '') === 'failed' ? 'text-rose-600 font-bold' : 'text-gray-400') }}">
                                {{ $stepItems['ai_rewrite']['time'] ?? (($stepItems['ai_rewrite']['status'] ?? '') === 'failed' ? 'FAILED' : 'Pending') }}
                            </span>
                        </div>

                        <!-- Step 3: Patch & Style Preservation -->
                        <div class="p-3.5 rounded-2xl border transition-all flex items-start justify-between gap-3 {{ ($stepItems['patch_html']['status'] ?? '') === 'success' ? 'bg-emerald-50/50 border-emerald-200' : (($stepItems['patch_html']['status'] ?? '') === 'failed' ? 'bg-rose-50 border-rose-200' : 'bg-gray-50 border-gray-200') }}">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-xl flex items-center justify-center font-bold text-xs {{ ($stepItems['patch_html']['status'] ?? '') === 'success' ? 'bg-emerald-100 text-emerald-700' : (($stepItems['patch_html']['status'] ?? '') === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-gray-200 text-gray-600') }}">
                                    @if(($stepItems['patch_html']['status'] ?? '') === 'success')
                                        <i class="fa-solid fa-check"></i>
                                    @elseif(($stepItems['patch_html']['status'] ?? '') === 'failed')
                                        <i class="fa-solid fa-xmark"></i>
                                    @else
                                        3
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">3. Preserve Inline Styles & Patch HTML</h4>
                                    <p class="text-[11px] text-gray-500">{{ $stepItems['patch_html']['details'] ?? 'Guarantees 100% gradient styles & DOM structure preservation' }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-mono font-semibold {{ ($stepItems['patch_html']['status'] ?? '') === 'success' ? 'text-emerald-700' : 'text-gray-400' }}">
                                {{ $stepItems['patch_html']['time'] ?? 'Pending' }}
                            </span>
                        </div>

                        <!-- Step 4: Git Push -->
                        <div class="p-3.5 rounded-2xl border transition-all flex items-start justify-between gap-3 {{ ($stepItems['git_sync']['status'] ?? '') === 'success' ? 'bg-emerald-50/50 border-emerald-200' : (($stepItems['git_sync']['status'] ?? '') === 'failed' ? 'bg-rose-50 border-rose-200 ring-2 ring-rose-500/20' : 'bg-gray-50 border-gray-200') }}">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-xl flex items-center justify-center font-bold text-xs {{ ($stepItems['git_sync']['status'] ?? '') === 'success' ? 'bg-emerald-100 text-emerald-700' : (($stepItems['git_sync']['status'] ?? '') === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-gray-200 text-gray-600') }}">
                                    @if(($stepItems['git_sync']['status'] ?? '') === 'success')
                                        <i class="fa-solid fa-check"></i>
                                    @elseif(($stepItems['git_sync']['status'] ?? '') === 'failed')
                                        <i class="fa-solid fa-xmark"></i>
                                    @else
                                        4
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">4. Git Commit & Auto-push to Remote</h4>
                                    <p class="text-[11px] text-gray-500">{{ $stepItems['git_sync']['details'] ?? 'Commits formatted changes to local repo & synchronizes GitHub branch' }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-mono font-semibold {{ ($stepItems['git_sync']['status'] ?? '') === 'success' ? 'text-emerald-700' : (($stepItems['git_sync']['status'] ?? '') === 'failed' ? 'text-rose-600 font-bold' : 'text-gray-400') }}">
                                {{ $stepItems['git_sync']['time'] ?? (($stepItems['git_sync']['status'] ?? '') === 'failed' ? 'FAILED' : 'Pending') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Granular Error Diagnostic Box (If Failure Occurs) -->
                @if(isset($workflowResult['success']) && !$workflowResult['success'])
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 space-y-2">
                        <div class="flex items-center gap-2 text-rose-800 font-bold text-xs">
                            <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                            Execution Failed at: {{ ucwords(str_replace('_', ' ', $workflowResult['failed_step'] ?? 'Pipeline Step')) }}
                        </div>
                        <p class="text-xs text-rose-700 font-mono bg-white/80 p-2.5 rounded-xl border border-rose-200 break-words">
                            {{ $workflowResult['error_message'] ?? 'Unknown error occurred during execution.' }}
                        </p>
                        <p class="text-[11px] text-rose-600 font-medium">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            Please check your AI Provider API Key or Git SSH connection and click Re-run Job below.
                        </p>
                    </div>
                @endif

                <!-- Modal Bottom Actions -->
                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <button
                        wire:click="closeWorkflowModal"
                        type="button"
                        class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 rounded-xl transition-colors"
                    >
                        Close
                    </button>

                    @if(isset($workflowResult['success']) && !$workflowResult['success'])
                        <button
                            wire:click="runNow({{ $activeRunningJobId }})"
                            type="button"
                            class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-lg shadow-rose-500/20 transition-all flex items-center gap-2 hover:scale-105"
                        >
                            <i class="fa-solid fa-rotate-right text-xs"></i>
                            <span>Re-run / Retry Job Now</span>
                        </button>
                    @else
                        <button
                            wire:click="closeWorkflowModal"
                            type="button"
                            class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2"
                        >
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>Done & Closed</span>
                        </button>
                    @endif
                </div>

            </div>
        </div>
    @endif
</div>

