<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#101828] tracking-tight">Connected Websites & Repositories</h1>
            <p class="text-xs text-[#667085] mt-1">Manage static sites, Git repository connections, and automated rewrite schedules</p>
        </div>
        <a
            href="{{ route('websites.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-colors self-start sm:self-auto"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Connect New Website
        </a>
    </div>

    <!-- Filter & Search Toolbar Card -->
    <div class="bg-white p-4 rounded-2xl border border-[#EAECF0] shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <!-- Search Input -->
        <div class="relative flex-1 max-w-md">
            <svg class="w-4 h-4 text-[#98A2B3] absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search websites by name, domain or repository..."
                class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
            >
        </div>

        <!-- Status Filter Pills -->
        <div class="flex items-center gap-1.5 p-1 bg-[#F2F4F7] rounded-xl border border-[#EAECF0] text-xs">
            <button
                wire:click="$set('statusFilter', 'all')"
                type="button"
                class="px-3 py-1.5 font-medium rounded-lg transition-all {{ $statusFilter === 'all' ? 'bg-white text-indigo-600 shadow-xs font-semibold' : 'text-[#667085] hover:text-[#101828]' }}"
            >
                All Sites
            </button>
            <button
                wire:click="$set('statusFilter', 'active')"
                type="button"
                class="px-3 py-1.5 font-medium rounded-lg transition-all {{ $statusFilter === 'active' ? 'bg-white text-indigo-600 shadow-xs font-semibold' : 'text-[#667085] hover:text-[#101828]' }}"
            >
                Active
            </button>
            <button
                wire:click="$set('statusFilter', 'paused')"
                type="button"
                class="px-3 py-1.5 font-medium rounded-lg transition-all {{ $statusFilter === 'paused' ? 'bg-white text-indigo-600 shadow-xs font-semibold' : 'text-[#667085] hover:text-[#101828]' }}"
            >
                Paused
            </button>
        </div>
    </div>

    <!-- Websites Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
        @forelse($websites as $website)
            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs hover:shadow-card transition-all duration-200 p-6 flex flex-col justify-between space-y-5 group relative">
                <!-- Top Info -->
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm flex-shrink-0 group-hover:scale-105 transition-transform">
                            {{ strtoupper(substr($website->domain ?? 'W', 0, 2)) }}
                        </div>
                        <div>
                            <a href="{{ route('websites.show', $website->id) }}" class="font-bold text-[#101828] hover:text-indigo-600 transition-colors text-base flex items-center gap-2">
                                {{ $website->name }}
                                <svg class="w-4 h-4 text-[#98A2B3] group-hover:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            </a>
                            <p class="text-xs text-[#667085] font-mono mt-0.5">{{ $website->domain }}</p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    @php
                        $statusVal = is_object($website->status) ? $website->status->value : $website->status;
                    @endphp
                    @if($statusVal === 'active')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-xs font-semibold">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Paused
                        </span>
                    @endif
                </div>

                <!-- Repository & Mode Meta Pills -->
                <div class="space-y-2 text-xs">
                    <div class="p-3 rounded-xl bg-[#F9FAFB] border border-[#EAECF0] flex items-center justify-between font-mono text-[11px] text-[#475467]">
                        <span class="truncate flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-[#98A2B3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            {{ $website->git_repository_url }}
                        </span>
                        <span class="px-2 py-0.5 rounded bg-white text-[#101828] border border-[#D0D5DD] font-semibold">
                            {{ $website->git_branch ?? 'main' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 text-[10px] rounded font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                {{ $website->approval_mode === 'automatic' ? 'Auto-Push' : 'Manual Review' }}
                            </span>
                            <span class="text-[#667085]">{{ $website->default_rewrite_interval_days ?? 30 }} {{ $website->default_rewrite_interval_unit ?? 'days' }} cycle</span>
                        </div>
                        <span class="text-[#667085] font-medium">{{ $website->pages_count ?? 0 }} Pages</span>
                    </div>
                </div>

                <!-- Card Footer Actions -->
                <div class="pt-4 border-t border-[#EAECF0] flex items-center justify-between gap-2 text-xs">
                    <button
                        wire:click="triggerSync({{ $website->id }})"
                        type="button"
                        class="px-3 py-1.5 rounded-lg border border-[#D0D5DD] bg-white hover:bg-[#F9FAFB] text-[#344054] font-semibold transition-colors flex items-center gap-1.5 shadow-xs"
                    >
                        <svg class="w-3.5 h-3.5 text-[#667085]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        Git Sync
                    </button>

                    <div class="flex items-center gap-2">
                        <a
                            href="{{ route('websites.edit', $website->id) }}"
                            class="px-3 py-1.5 rounded-lg border border-[#D0D5DD] bg-white hover:bg-[#F9FAFB] text-[#344054] font-semibold transition-colors shadow-xs"
                        >
                            Settings
                        </a>
                        <a
                            href="{{ route('websites.show', $website->id) }}"
                            class="px-3 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition-colors"
                        >
                            View Pages →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-2xl border border-[#EAECF0] p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                </div>
                <h3 class="text-base font-bold text-[#101828]">No Websites Found</h3>
                <p class="text-xs text-[#667085] mt-1 max-w-sm mx-auto">No websites match your search or filter parameters. Connect your first static site repository to start automated AI content refreshes.</p>
                <a href="{{ route('websites.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold text-xs shadow-xs hover:bg-indigo-700">
                    Connect Website Now
                </a>
            </div>
        @endforelse
    </div>
</div>
