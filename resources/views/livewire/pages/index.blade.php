<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#101828] tracking-tight">Tracked Website Pages</h1>
            <p class="text-xs text-[#667085] mt-1">Catalog of all monitored HTML, Markdown, and static content paths across websites</p>
        </div>

        @if(count($selectedPages) > 0)
            <button
                wire:click="triggerBatchRewrite"
                type="button"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-colors self-start sm:self-auto"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                Run AI Refresh on {{ count($selectedPages) }} Selected
            </button>
        @endif
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-[#EAECF0] shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <!-- Search Input -->
        <div class="relative flex-1 max-w-md">
            <svg class="w-4 h-4 text-[#98A2B3] absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search by page path or friendly name..."
                class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
            >
        </div>

        <!-- Website Filter Dropdown -->
        <div class="flex items-center gap-2">
            <select
                wire:model.live="websiteFilter"
                class="px-3.5 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
            >
                <option value="all">All Connected Sites</option>
                @foreach($websites as $site)
                    <option value="{{ $site->id }}">{{ $site->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Pages Main Table -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#EAECF0] text-[#667085] font-semibold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4 w-10">
                            <input type="checkbox" wire:model.live="selectAll" class="rounded border-[#D0D5DD] text-indigo-600 focus:ring-indigo-500">
                        </th>
                        <th class="py-3 px-4">Page Path & Name</th>
                        <th class="py-3 px-4">Website</th>
                        <th class="py-3 px-4">Word Count</th>
                        <th class="py-3 px-4">Last Refreshed</th>
                        <th class="py-3 px-4">Next Due Run</th>
                        <th class="py-3 px-4">Model</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAECF0]">
                    @foreach($pages as $page)
                        <tr class="hover:bg-[#F9FAFB]/80 transition-colors">
                            <td class="py-3.5 px-4">
                                <input type="checkbox" wire:model.live="selectedPages" value="{{ $page->id }}" class="rounded border-[#D0D5DD] text-indigo-600 focus:ring-indigo-500">
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="{{ route('pages.show', $page->id) }}" class="font-mono font-semibold text-[#101828] hover:text-indigo-600 transition-colors block">
                                    {{ $page->path }}
                                </a>
                                <span class="text-[11px] text-[#667085]">{{ $page->friendly_name ?? 'Static Document' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-[#344054]">
                                <span class="font-medium text-[#101828] block">{{ $page->website->name ?? ($page->website_name ?? 'TechCorp Docs') }}</span>
                                <span class="text-[10px] text-[#667085] font-mono">{{ $page->website->domain ?? ($page->domain ?? 'techcorp.io') }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-[#344054]">
                                {{ number_format($page->word_count ?? 1240) }} words
                            </td>
                            <td class="py-3.5 px-4 text-[#667085]">
                                @if(isset($page->last_rewrite_at) && $page->last_rewrite_at)
                                    {{ is_string($page->last_rewrite_at) ? $page->last_rewrite_at : $page->last_rewrite_at->diffForHumans() }}
                                @else
                                    <span class="text-[#98A2B3]">Never</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if(isset($page->next_rewrite_at) && $page->next_rewrite_at)
                                    <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold border border-blue-200">
                                        {{ is_string($page->next_rewrite_at) ? $page->next_rewrite_at : $page->next_rewrite_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-gray-50 text-[#667085] font-medium border border-gray-200">Disabled</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 text-[10px] rounded font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                    {{ $page->ai_model ?? 'GPT-4o' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        wire:click="triggerRewrite({{ $page->id }})"
                                        type="button"
                                        class="px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition-colors"
                                    >
                                        Rewrite Now
                                    </button>
                                    <a
                                        href="{{ route('pages.show', $page->id) }}"
                                        class="p-1 rounded-lg hover:bg-[#F2F4F7] text-[#667085] hover:text-[#101828]"
                                        title="View Page Details"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 bg-[#F9FAFB] border-t border-[#EAECF0] flex items-center justify-between text-xs text-[#667085]">
            <span>Showing {{ count($pages) }} tracked pages</span>
            <span>Autoflow Page Engine</span>
        </div>
    </div>
</div>
