<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">Connected Websites & Repositories</h1>
            <p class="text-xs text-[#64748B] mt-1">Manage static sites, Git repository connections, and automated rewrite schedules</p>
        </div>
        @if(auth()->user()?->hasActiveSubscription())
            <a
                href="{{ route('websites.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs shadow-sm transition-all hover:scale-105 self-start sm:self-auto"
            >
                <i class="fa-solid fa-plus text-xs"></i>
                Connect New Website
            </a>
        @else
            <button
                @click="$dispatch('open-paywall', { feature: 'Website Connection & Sync' })"
                type="button"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs shadow-sm transition-all hover:scale-105 self-start sm:self-auto"
            >
                <i class="fa-solid fa-lock text-xs"></i>
                Connect New Website
            </button>
        @endif
    </div>

    <!-- Filter & Search Toolbar Card -->
    <div class="bg-white p-4 rounded-2xl border border-[#E2E8F0] shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <!-- Search Input -->
        <div class="relative flex-1 max-w-md">
            <i class="fa-solid fa-magnifying-glass text-[#94A3B8] absolute left-3.5 top-3 text-xs"></i>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search websites by name, domain or repository..."
                class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-[#CBD5E1] bg-[#F8FAFC] focus:bg-white text-[#0F172A] placeholder-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/20 focus:border-[#22C55E] transition-all"
            >
        </div>

        <!-- Status Filter Pills -->
        <div class="flex items-center gap-1.5 p-1 bg-[#F1F5F9] rounded-xl border border-[#E2E8F0] text-xs">
            <button
                wire:click="$set('statusFilter', 'all')"
                type="button"
                class="px-3 py-1.5 font-semibold rounded-lg transition-all {{ $statusFilter === 'all' ? 'bg-white text-[#15803D] shadow-xs' : 'text-[#64748B] hover:text-[#0F172A]' }}"
            >
                All Sites
            </button>
            <button
                wire:click="$set('statusFilter', 'active')"
                type="button"
                class="px-3 py-1.5 font-semibold rounded-lg transition-all {{ $statusFilter === 'active' ? 'bg-white text-[#15803D] shadow-xs' : 'text-[#64748B] hover:text-[#0F172A]' }}"
            >
                Active
            </button>
            <button
                wire:click="$set('statusFilter', 'paused')"
                type="button"
                class="px-3 py-1.5 font-semibold rounded-lg transition-all {{ $statusFilter === 'paused' ? 'bg-white text-[#15803D] shadow-xs' : 'text-[#64748B] hover:text-[#0F172A]' }}"
            >
                Paused
            </button>
        </div>
    </div>

    <!-- Websites Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
        @forelse($websites as $website)
            <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-card hover:border-[#22C55E] transition-all duration-200 p-6 flex flex-col justify-between space-y-5 group relative">
                <!-- Top Info -->
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#F0FDF4] border border-[#DCFCE7] text-[#22C55E] flex items-center justify-center font-bold text-sm flex-shrink-0 group-hover:scale-105 transition-transform">
                            {{ strtoupper(substr($website->domain ?? 'W', 0, 2)) }}
                        </div>
                        <div>
                            <a href="{{ route('websites.show', $website->id) }}" class="font-bold text-[#0F172A] hover:text-[#22C55E] transition-colors text-base flex items-center gap-2">
                                {{ $website->name }}
                                <i class="fa-solid fa-arrow-up-right-from-square text-[#94A3B8] group-hover:text-[#22C55E] transition-colors text-xs"></i>
                            </a>
                            <p class="text-xs text-[#64748B] font-mono mt-0.5">{{ $website->domain }}</p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    @php
                        $statusVal = is_object($website->status) ? $website->status->value : $website->status;
                    @endphp
                    @if($statusVal === 'active')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#DCFCE7] text-[#15803D] border border-[#BBF7D0] text-xs font-semibold">
                            <span class="w-2 h-2 rounded-full bg-[#22C55E] animate-pulse"></span>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#FEF3C7] text-[#B45309] border border-[#FDE68A] text-xs font-semibold">
                            <span class="w-2 h-2 rounded-full bg-[#F59E0B]"></span>
                            Paused
                        </span>
                    @endif
                </div>

                <!-- Repository & Mode Meta Pills -->
                <div class="space-y-2 text-xs">
                    <div class="p-3 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] flex items-center justify-between font-mono text-[11px] text-[#475569]">
                        <span class="truncate flex items-center gap-2">
                            <i class="fa-brands fa-github text-[#64748B] text-sm"></i>
                            {{ $website->git_repository_url }}
                        </span>
                        <span class="px-2 py-0.5 rounded bg-white text-[#0F172A] border border-[#CBD5E1] font-semibold">
                            {{ $website->git_branch ?? 'main' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 text-[10px] rounded font-semibold bg-[#EDE9FE] text-[#6D28D9] border border-[#DDD6FE]">
                                {{ $website->approval_mode === 'automatic' ? 'Auto-Push' : 'Manual Review' }}
                            </span>
                            <span class="text-[#64748B]">{{ $website->default_rewrite_interval_days ?? 30 }} {{ $website->default_rewrite_interval_unit ?? 'days' }} cycle</span>
                        </div>
                        <span class="text-[#64748B] font-medium">{{ $website->pages_count ?? 0 }} Pages</span>
                    </div>
                </div>

                <!-- Card Footer Actions -->
                <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-between gap-2 text-xs">
                    <button
                        wire:click="triggerSync({{ $website->id }})"
                        type="button"
                        class="px-3 py-1.5 rounded-lg border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#334155] font-semibold transition-colors flex items-center gap-1.5 shadow-xs"
                    >
                        <i class="fa-solid fa-rotate text-[#64748B] text-xs"></i>
                        Git Sync
                    </button>

                    <div class="flex items-center gap-2">
                        <a
                            href="{{ route('websites.edit', $website->id) }}"
                            class="px-3 py-1.5 rounded-lg border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#334155] font-semibold transition-colors shadow-xs"
                        >
                            Settings
                        </a>
                        <a
                            href="{{ route('websites.show', $website->id) }}"
                            class="px-3 py-1.5 rounded-lg bg-[#F0FDF4] hover:bg-[#DCFCE7] text-[#15803D] font-bold transition-colors flex items-center gap-1"
                        >
                            View Pages <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-2xl border border-[#E2E8F0] p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-[#F0FDF4] text-[#22C55E] border border-[#DCFCE7] flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-globe text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-[#0F172A]">No Websites Found</h3>
                <p class="text-xs text-[#64748B] mt-1 max-w-sm mx-auto">No websites match your search or filter parameters. Connect your first static site repository to start automated AI content refreshes.</p>
                <a href="{{ route('websites.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#22C55E] text-white font-bold text-xs shadow-xs hover:bg-[#16A34A]">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Connect Website Now
                </a>
            </div>
        @endforelse
    </div>
</div>
