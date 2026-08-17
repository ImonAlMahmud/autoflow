<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300 flex items-center gap-1">
                    <i class="fa-solid fa-crown text-amber-600 text-[10px]"></i> Super Admin Control
                </span>
                <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">All Users' Tracked HTML Pages</h1>
            </div>
            <p class="text-xs text-[#64748B] mt-1">Master catalog of all static HTML pages and documentation assets being tracked and refreshed across all client websites.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-white border border-[#CBD5E1] text-xs font-bold text-[#334155] shadow-xs">
                Total Pages: <strong class="text-[#15803D]">{{ $pages->total() }}</strong>
            </span>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-card p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="relative flex-1 max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-[#94A3B8]"></i>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search by page path, website, domain, or user email..."
                class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-[#CBD5E1] bg-[#F8FAFC] focus:bg-white text-[#0F172A] focus:ring-2 focus:ring-[#22C55E] transition-all"
            >
        </div>

        <div class="flex items-center gap-2">
            <select
                wire:model.live="websiteFilter"
                class="px-3 py-2 text-xs rounded-xl border border-[#CBD5E1] bg-white text-[#0F172A] font-medium focus:ring-2 focus:ring-[#22C55E]"
            >
                <option value="all">All Websites ({{ $websites->count() }})</option>
                @foreach($websites as $w)
                    <option value="{{ $w->id }}">{{ $w->name }} ({{ $w->domain }})</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Pages Table -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#334155]">
                <thead class="bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#64748B] border-b border-[#E2E8F0]">
                    <tr>
                        <th class="px-5 py-3.5">Page Path & Friendly Name</th>
                        <th class="px-5 py-3.5">Website & Domain</th>
                        <th class="px-5 py-3.5">Owner / User</th>
                        <th class="px-5 py-3.5">Word Count</th>
                        <th class="px-5 py-3.5">Last Refreshed</th>
                        <th class="px-5 py-3.5">Next Cycle</th>
                        <th class="px-5 py-3.5">AI Engine</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($pages as $page)
                        <tr class="hover:bg-[#F8FAFC]/80 transition-colors">
                            <!-- Page Path -->
                            <td class="px-5 py-4">
                                <a href="{{ route('pages.show', $page->id) }}" class="font-mono font-bold text-[#0F172A] hover:text-[#15803D] transition-colors block">
                                    {{ $page->path }}
                                </a>
                                <span class="text-[11px] text-[#64748B]">{{ $page->friendly_name ?? 'Static HTML Page' }}</span>
                            </td>

                            <!-- Website & Domain -->
                            <td class="px-5 py-4">
                                <span class="font-bold text-[#0F172A] block">{{ $page->website->name ?? 'Deleted Site' }}</span>
                                <span class="text-[10px] text-[#64748B] font-mono">{{ $page->website->domain ?? 'N/A' }}</span>
                            </td>

                            <!-- Owner / User -->
                            <td class="px-5 py-4">
                                @if($page->website?->user)
                                    <div class="font-semibold text-[#0F172A] flex items-center gap-1.5">
                                        <div class="w-4 h-4 rounded-full bg-emerald-100 text-[#15803D] font-bold text-[9px] flex items-center justify-center">
                                            {{ strtoupper(substr($page->website->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span>{{ $page->website->user->name }}</span>
                                    </div>
                                    <div class="text-[10px] text-[#64748B] font-mono">{{ $page->website->user->email }}</div>
                                @else
                                    <span class="text-[11px] text-gray-400 italic">System / Superadmin</span>
                                @endif
                            </td>

                            <!-- Word Count -->
                            <td class="px-5 py-4 font-medium text-[#334155]">
                                @if($page->word_count)
                                    {{ number_format($page->word_count) }} words
                                @else
                                    <span class="text-gray-400 italic text-[11px]">Auto on run</span>
                                @endif
                            </td>

                            <!-- Last Refreshed -->
                            <td class="px-5 py-4 text-[#64748B]">
                                @if($page->last_rewrite_at)
                                    {{ $page->last_rewrite_at->diffForHumans() }}
                                @else
                                    <span class="text-[#94A3B8]">Never</span>
                                @endif
                            </td>

                            <!-- Next Cycle -->
                            <td class="px-5 py-4">
                                @if($page->next_rewrite_at)
                                    <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold border border-blue-200 text-[10px]">
                                        {{ $page->next_rewrite_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-gray-50 text-[#64748B] font-medium border border-gray-200 text-[10px]">On Schedule</span>
                                @endif
                            </td>

                            <!-- AI Engine -->
                            <td class="px-5 py-4">
                                @php
                                    $modelDisplayName = $page->aiModel?->name ?? ($page->website?->defaultAiModel?->name ?? 'Llama 3.3 70B');
                                @endphp
                                <span class="px-2 py-0.5 text-[10px] rounded font-semibold bg-emerald-50 text-[#15803D] border border-emerald-200">
                                    {{ $modelDisplayName }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right">
                                <a
                                    href="{{ route('pages.show', $page->id) }}"
                                    class="px-2.5 py-1.5 rounded-lg border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#15803D] font-bold text-xs transition-all shadow-xs inline-flex items-center gap-1"
                                    title="View Page Details"
                                >
                                    <span>View</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-xs text-[#64748B]">
                                <i class="fa-solid fa-file-code text-3xl text-[#CBD5E1] mb-2 block"></i>
                                No tracked pages found across the system.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pages->hasPages())
            <div class="p-4 border-t border-[#E2E8F0] bg-[#F8FAFC]">
                {{ $pages->links() }}
            </div>
        @endif
    </div>
</div>
