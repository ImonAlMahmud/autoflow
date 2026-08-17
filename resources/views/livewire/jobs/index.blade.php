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

    {{-- ══════════════════════════════════════════════════════════════════
         N8N-STYLE ANIMATED PIPELINE LIFECYCLE MODAL
    ══════════════════════════════════════════════════════════════════ --}}
    @if($showWorkflowModal)
    <style>
        @@keyframes flow-dot {
            0%   { transform: translateY(-100%); opacity: 0; }
            20%  { opacity: 1; }
            80%  { opacity: 1; }
            100% { transform: translateY(100%); opacity: 0; }
        }
        @@keyframes node-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(99,102,241,.5); }
            50%       { box-shadow: 0 0 0 8px rgba(99,102,241,0); }
        }
        @@keyframes success-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34,197,94,.4); }
            50%       { box-shadow: 0 0 0 10px rgba(34,197,94,0); }
        }
        @@keyframes slide-in-up {
            from { opacity:0; transform:translateY(20px) scale(.97); }
            to   { opacity:1; transform:translateY(0)   scale(1);    }
        }
        .pipeline-modal   { animation: slide-in-up .25s cubic-bezier(.22,.68,0,1.2) both; }
        .node-pending     { animation: node-pulse 1.8s ease-in-out infinite; }
        .node-success     { animation: success-glow 2s ease-in-out infinite; }
        .flow-line        { position:relative; width:2px; margin:0 auto; overflow:hidden; }
        .flow-dot         { position:absolute; width:4px; height:14px; left:-1px; border-radius:9px; animation: flow-dot 1.2s linear infinite; }
    </style>

    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4"
         style="background:rgba(2,6,23,.75); backdrop-filter:blur(8px);">

        <div class="pipeline-modal bg-[#0F1117] rounded-3xl w-full max-w-lg shadow-2xl border border-white/[.06] overflow-hidden">

            {{-- ── HEADER ── --}}
            <div class="relative px-6 pt-6 pb-5 border-b border-white/[.07]"
                 style="background:linear-gradient(135deg,#1e1b4b 0%,#111827 100%)">
                {{-- decorative blobs --}}
                <div class="absolute inset-0 pointer-events-none overflow-hidden rounded-t-3xl">
                    <div class="absolute -top-6 -left-6 w-32 h-32 rounded-full opacity-20"
                         style="background:radial-gradient(circle,#6366f1,transparent)"></div>
                    <div class="absolute -bottom-4 right-10 w-24 h-24 rounded-full opacity-10"
                         style="background:radial-gradient(circle,#22c55e,transparent)"></div>
                </div>

                <div class="relative flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        {{-- status icon --}}
                        @if($workflowResult['success'] ?? false)
                            <div class="w-11 h-11 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center node-success">
                                <i class="fa-solid fa-circle-check text-emerald-400 text-xl"></i>
                            </div>
                        @elseif(!empty($workflowResult) && !($workflowResult['success'] ?? true))
                            <div class="w-11 h-11 rounded-2xl bg-rose-500/20 border border-rose-500/40 flex items-center justify-center">
                                <i class="fa-solid fa-triangle-exclamation text-rose-400 text-xl"></i>
                            </div>
                        @else
                            <div class="w-11 h-11 rounded-2xl bg-indigo-500/20 border border-indigo-500/40 flex items-center justify-center node-pending">
                                <i class="fa-solid fa-microchip text-indigo-400 text-xl fa-beat-fade"></i>
                            </div>
                        @endif
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-mono font-bold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-2 py-0.5 rounded-full">
                                    JOB #{{ $activeRunningJobId }}
                                </span>
                                @if($workflowResult['success'] ?? false)
                                    <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-full flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span> COMPLETED
                                    </span>
                                @elseif(!empty($workflowResult) && !($workflowResult['success'] ?? true))
                                    <span class="text-[10px] font-bold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded-full flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400 inline-block"></span> FAILED
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-2 py-0.5 rounded-full flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 inline-block animate-pulse"></span> RUNNING
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-white font-extrabold text-base mt-1 tracking-tight">Live Pipeline Execution</h3>
                            <p class="text-white/40 text-[11px] mt-0.5">AI Content Rewrite & Git Deploy Workflow</p>
                        </div>
                    </div>
                    <button wire:click="closeWorkflowModal" type="button"
                            class="w-8 h-8 rounded-xl bg-white/5 hover:bg-white/10 text-white/40 hover:text-white/80 flex items-center justify-center transition-all">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            </div>

            {{-- ── PIPELINE NODES ── --}}
            <div class="px-6 py-5 space-y-0">
                @php
                    $stepItems = $workflowResult['steps'] ?? [
                        'extract'    => ['status' => 'pending'],
                        'ai_rewrite' => ['status' => 'pending'],
                        'patch_html' => ['status' => 'pending'],
                        'git_sync'   => ['status' => 'pending'],
                    ];

                    $pipelineNodes = [
                        'extract' => [
                            'icon'    => 'fa-solid fa-magnifying-glass-chart',
                            'label'   => 'Extract HTML Content',
                            'sub'     => 'Scan & isolate rewritable segments',
                            'color'   => 'violet',
                            'iconBg'  => '#7c3aed',
                        ],
                        'ai_rewrite' => [
                            'icon'    => 'fa-solid fa-robot',
                            'label'   => 'AI Rewrite Engine',
                            'sub'     => 'SEO-optimized content generation',
                            'color'   => 'blue',
                            'iconBg'  => '#2563eb',
                        ],
                        'patch_html' => [
                            'icon'    => 'fa-solid fa-code',
                            'label'   => 'Patch & Preserve HTML',
                            'sub'     => 'Inject rewritten text, keep all styles',
                            'color'   => 'cyan',
                            'iconBg'  => '#0891b2',
                        ],
                        'git_sync' => [
                            'icon'    => 'fa-brands fa-github',
                            'label'   => 'Git Commit & Deploy',
                            'sub'     => 'Push to GitHub → trigger Vercel build',
                            'color'   => 'emerald',
                            'iconBg'  => '#059669',
                        ],
                    ];
                @endphp

                @foreach($pipelineNodes as $key => $node)
                    @php
                        $st      = $stepItems[$key]['status'] ?? 'pending';
                        $details = $stepItems[$key]['details'] ?? null;
                        $time    = $stepItems[$key]['time'] ?? null;
                        $isLast  = $key === 'git_sync';

                        $borderColor = match($st) {
                            'success' => 'border-emerald-500/40',
                            'failed'  => 'border-rose-500/50',
                            default   => 'border-white/[.08]',
                        };
                        $bgColor = match($st) {
                            'success' => 'background:linear-gradient(135deg,rgba(16,185,129,.08),rgba(16,185,129,.03))',
                            'failed'  => 'background:linear-gradient(135deg,rgba(239,68,68,.10),rgba(239,68,68,.04))',
                            default   => 'background:rgba(255,255,255,.025)',
                        };
                    @endphp

                    {{-- NODE CARD --}}
                    <div class="relative">
                        <div class="flex items-start gap-4 p-4 rounded-2xl border {{ $borderColor }} transition-all duration-300"
                             style="{{ $bgColor }}">

                            {{-- icon badge --}}
                            <div class="relative flex-shrink-0">
                                @if($st === 'success')
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center node-success"
                                         style="background:rgba(16,185,129,.18); border:1px solid rgba(16,185,129,.35)">
                                        <i class="fa-solid fa-check text-emerald-400 text-sm"></i>
                                    </div>
                                @elseif($st === 'failed')
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                         style="background:rgba(239,68,68,.18); border:1px solid rgba(239,68,68,.35)">
                                        <i class="fa-solid fa-xmark text-rose-400 text-sm"></i>
                                    </div>
                                @elseif(empty($workflowResult) || ($st === 'pending' && $key === array_key_first(array_filter($pipelineNodes, fn($n) => ($stepItems[array_search($n, $pipelineNodes)]['status'] ?? '') === 'pending'))))
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center node-pending"
                                         style="background:rgba(99,102,241,.18); border:1px solid rgba(99,102,241,.4)">
                                        <i class="{{ $node['icon'] }} text-indigo-400 text-sm fa-beat-fade"></i>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                         style="background:{{ $node['iconBg'] }}22; border:1px solid {{ $node['iconBg'] }}44">
                                        <i class="{{ $node['icon'] }} text-white/30 text-sm"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- text --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-xs font-bold {{ $st === 'success' ? 'text-emerald-300' : ($st === 'failed' ? 'text-rose-300' : 'text-white/70') }}">
                                        {{ $node['label'] }}
                                    </span>
                                    @if($time)
                                        <span class="text-[10px] font-mono font-semibold flex-shrink-0 {{ $st === 'success' ? 'text-emerald-400' : ($st === 'failed' ? 'text-rose-400' : 'text-white/25') }}">
                                            {{ $time }}
                                        </span>
                                    @elseif($st === 'failed')
                                        <span class="text-[10px] font-mono font-bold text-rose-400 bg-rose-500/10 px-1.5 py-0.5 rounded">FAILED</span>
                                    @elseif($st === 'pending')
                                        <span class="text-[10px] text-white/20 font-mono">—</span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-white/35 mt-0.5 truncate">
                                    {{ $details ?? $node['sub'] }}
                                </p>
                            </div>
                        </div>

                        {{-- CONNECTOR LINE (between nodes) --}}
                        @if(!$isLast)
                            <div class="flex justify-start pl-9 py-0.5">
                                <div class="flow-line h-6" style="background:rgba(255,255,255,.07);">
                                    @if($st === 'success')
                                        <div class="flow-dot bg-emerald-400" style="animation-delay:.0s;"></div>
                                        <div class="flow-dot bg-emerald-300 opacity-70" style="animation-delay:.4s;"></div>
                                    @elseif(empty($workflowResult))
                                        <div class="flow-dot bg-indigo-400 opacity-60" style="animation-delay:.0s;"></div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- ── ERROR PANEL ── --}}
            @if(isset($workflowResult['success']) && !$workflowResult['success'])
                <div class="mx-6 mb-5 rounded-2xl overflow-hidden border border-rose-500/25"
                     style="background:linear-gradient(135deg,rgba(239,68,68,.08),rgba(239,68,68,.03))">
                    <div class="flex items-center gap-2 px-4 py-2.5 border-b border-rose-500/15">
                        <div class="w-5 h-5 rounded-lg bg-rose-500/20 flex items-center justify-center">
                            <i class="fa-solid fa-circle-exclamation text-rose-400 text-[10px]"></i>
                        </div>
                        <span class="text-xs font-bold text-rose-300">
                            Failed at: {{ ucwords(str_replace('_', ' ', $workflowResult['failed_step'] ?? 'Pipeline Step')) }}
                        </span>
                    </div>
                    <div class="px-4 py-3">
                        <code class="text-[11px] text-rose-200/80 font-mono leading-relaxed block break-all">
                            {{ $workflowResult['error_message'] ?? 'Unknown error occurred.' }}
                        </code>
                        <p class="text-[11px] text-rose-400/60 mt-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-lightbulb"></i>
                            Check your AI Provider API Key / endpoint and retry.
                        </p>
                    </div>
                </div>
            @endif

            {{-- ── SUCCESS BANNER ── --}}
            @if($workflowResult['success'] ?? false)
                @php $isPending = $workflowResult['is_pending_review'] ?? false; @endphp
                <div class="mx-6 mb-5 rounded-2xl px-4 py-3 flex items-center gap-3 border {{ $isPending ? 'border-amber-500/25 bg-amber-500/[.07]' : 'border-emerald-500/25 bg-emerald-500/[.07]' }}">
                    <i class="fa-solid {{ $isPending ? 'fa-clock text-amber-400' : 'fa-rocket text-emerald-400' }} text-lg"></i>
                    <div>
                        <p class="text-xs font-bold {{ $isPending ? 'text-amber-300' : 'text-emerald-300' }}">
                            {{ $isPending ? 'Awaiting Your Approval' : 'Deployed to GitHub & Vercel' }}
                        </p>
                        <p class="text-[11px] {{ $isPending ? 'text-amber-400/60' : 'text-emerald-400/60' }} mt-0.5">
                            {{ $isPending ? 'Review the diff and click Approve & Push when ready.' : 'Changes are live. Vercel build triggered automatically.' }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- ── FOOTER ACTIONS ── --}}
            <div class="px-6 pb-6 flex items-center justify-between gap-3 border-t border-white/[.06] pt-4">
                <button wire:click="closeWorkflowModal" type="button"
                        class="px-4 py-2 text-xs font-semibold text-white/40 hover:text-white/70 hover:bg-white/5 rounded-xl transition-all">
                    {{ (isset($workflowResult['success']) && $workflowResult['success']) ? '✓ Close' : 'Close' }}
                </button>

                @if(isset($workflowResult['success']) && !$workflowResult['success'])
                    <button wire:click="runNow({{ $activeRunningJobId }})" type="button"
                            class="px-5 py-2.5 rounded-xl font-bold text-xs text-white flex items-center gap-2 transition-all hover:scale-105 active:scale-95"
                            style="background:linear-gradient(135deg,#dc2626,#b91c1c); box-shadow:0 4px 16px rgba(220,38,38,.35)">
                        <i class="fa-solid fa-rotate-right"></i>
                        Re-run / Retry Job
                    </button>
                @else
                    <button wire:click="closeWorkflowModal" type="button"
                            class="px-5 py-2.5 rounded-xl font-bold text-xs text-white flex items-center gap-2 transition-all hover:scale-105 active:scale-95"
                            style="background:linear-gradient(135deg,#16a34a,#15803d); box-shadow:0 4px 16px rgba(22,163,74,.35)">
                        <i class="fa-solid fa-check"></i>
                        Done
                    </button>
                @endif
            </div>

        </div>
    </div>
    @endif
</div>

