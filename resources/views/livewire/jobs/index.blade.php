<div class="space-y-6" x-data="{ showCreateModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#101828] tracking-tight">Automation & Rewrite Jobs</h1>
            <p class="text-xs text-[#667085] mt-1">Live queue of background content generation, validation, and Git commit tasks</p>
        </div>

        <!-- + Dispatch New Job Button -->
        <button
            @click="showCreateModal = true"
            type="button"
            class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-colors flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            + Run / Dispatch New Job
        </button>
    </div>

    <!-- SECTION 1: WEBSITE FILTER TABS (FOR MANAGING 40-50+ SITES EASILY) -->
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-[#344054] uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                Filter Jobs By Website ({{ $websites->count() }} Managed Sites)
            </span>
            @if($websiteFilter)
                <button wire:click="selectWebsiteFilter(null)" type="button" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                    Clear Site Filter ✕
                </button>
            @endif
        </div>

        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-thin">
            <!-- All Websites Tab -->
            <button
                wire:click="selectWebsiteFilter(null)"
                type="button"
                class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 whitespace-nowrap shadow-xs {{ is_null($websiteFilter) ? 'bg-indigo-600 text-white ring-2 ring-indigo-600/30' : 'bg-white text-[#344054] border border-[#EAECF0] hover:bg-[#F9FAFB]' }}"
            >
                <span>🌐 All Websites</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ is_null($websiteFilter) ? 'bg-indigo-700 text-white' : 'bg-[#F2F4F7] text-[#475467]' }}">
                    {{ $totalJobsCount }}
                </span>
            </button>

            <!-- Individual Website Tabs -->
            @foreach($websites as $site)
                <button
                    wire:click="selectWebsiteFilter({{ $site->id }})"
                    type="button"
                    class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all flex items-center gap-2 whitespace-nowrap shadow-xs {{ $websiteFilter === $site->id ? 'bg-indigo-600 text-white ring-2 ring-indigo-600/30' : 'bg-white text-[#344054] border border-[#EAECF0] hover:bg-[#F9FAFB]' }}"
                >
                    <span class="truncate max-w-[180px]">{{ $site->name }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $websiteFilter === $site->id ? 'bg-indigo-700 text-white' : 'bg-indigo-50 text-indigo-700 border border-indigo-100' }}">
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
            <svg class="w-4 h-4 text-[#98A2B3] absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search jobs by path or job ID..."
                class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
            >
        </div>

        <!-- Filter Status Pills -->
        <div class="flex items-center gap-1 p-1 bg-[#F2F4F7] rounded-xl border border-[#EAECF0] text-xs">
            <button
                wire:click="$set('statusFilter', 'all')"
                class="px-3 py-1.5 font-medium rounded-lg transition-all {{ $statusFilter === 'all' ? 'bg-white text-indigo-600 shadow-xs font-semibold' : 'text-[#667085]' }}"
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
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ is_object($job->status) ? $job->status->label() : strtoupper($job->status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#667085]">
                                {{ $job->created_at ? \Carbon\Carbon::parse($job->created_at)->timezone('Asia/Dhaka')->diffForHumans() : 'Just now' }}
                            </td>
                            <td class="py-3.5 px-4">
                                @php
                                    $statusVal = is_object($job->status) ? $job->status->value : (string)$job->status;
                                @endphp
                                @if(in_array($statusVal, ['cancelled', 'completed', 'failed', 'skipped']))
                                    <span class="text-[#98A2B3] text-xs font-mono">—</span>
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
                                        <svg class="w-3.5 h-3.5 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span x-text="timerText"></span>
                                        <span class="text-[10px] text-blue-500 font-sans">({{ $dueTime->format('h:i A') }})</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right flex items-center justify-end gap-2">
                                @if(!in_array($statusVal, ['completed', 'cancelled', 'failed']))
                                    <button
                                        type="button"
                                        wire:click.prevent="runNow({{ $job->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="runNow({{ $job->id }})"
                                        title="Run & Execute Job Instantly Now"
                                        class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 disabled:bg-indigo-600 disabled:opacity-90 disabled:cursor-wait text-white font-semibold text-xs transition-all inline-flex items-center gap-1.5 shadow-xs"
                                    >
                                        <!-- Default State -->
                                        <span wire:loading.remove wire:target="runNow({{ $job->id }})" class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
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
                                <a href="{{ route('jobs.show', $job->id) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs">Details →</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-4 text-center">
                                <div class="max-w-md mx-auto space-y-3">
                                    <p class="text-xs text-[#667085]">No automation jobs dispatched yet.</p>
                                    <button @click="showCreateModal = true" type="button" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-all">
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
                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-bold text-[11px] border border-indigo-200">
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
                <button wire:click="createJob" @click="showCreateModal = false" type="button" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl">Run AI Job Now</button>
            </div>
        </div>
    </div>
</div>
