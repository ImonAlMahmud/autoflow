<div class="space-y-6">
    <!-- Website Title & Header Card -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-[#15803D] flex items-center justify-center font-bold text-lg flex-shrink-0 shadow-xs">
                    {{ strtoupper(substr($website->domain ?? 'W', 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold text-[#101828] tracking-tight">{{ $website->name }}</h1>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Active Git Remote
                        </span>
                    </div>
                    <p class="text-xs text-[#667085] font-mono mt-0.5">{{ $website->domain }} • {{ $website->git_repository_url }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <button
                    wire:click="triggerFullSync"
                    type="button"
                    class="px-3.5 py-2 rounded-xl border border-[#D0D5DD] bg-white hover:bg-[#F9FAFB] text-xs font-semibold text-[#344054] transition-colors flex items-center gap-1.5 shadow-xs"
                >
                    <i class="fa-solid fa-rotate text-xs"></i>
                    Git Sync
                </button>
                <button
                    wire:click="runAudit"
                    type="button"
                    class="px-3.5 py-2 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white text-xs font-semibold transition-colors shadow-xs"
                >
                    Run Content Audit
                </button>
                <a
                    href="{{ route('websites.edit', $website->id) }}"
                    class="p-2 rounded-xl border border-[#D0D5DD] bg-white hover:bg-[#F9FAFB] text-[#667085] transition-colors shadow-xs"
                    title="Website Settings"
                >
                    <i class="fa-solid fa-gear text-xs"></i>
                </a>
            </div>
        </div>

        <!-- 4 Quick Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-[#EAECF0]">
            <div>
                <span class="text-[11px] text-[#667085] uppercase font-semibold">Total Tracked Pages</span>
                <p class="text-lg font-bold text-[#101828] mt-0.5">{{ count($pages) }}</p>
            </div>
            <div>
                <span class="text-[11px] text-[#667085] uppercase font-semibold">Git Target Branch</span>
                <p class="text-sm font-bold font-mono text-[#15803D] mt-1">{{ $website->git_branch ?? 'main' }}</p>
            </div>
            <div>
                <span class="text-[11px] text-[#667085] uppercase font-semibold">Approval Mode</span>
                <p class="text-xs font-bold text-[#101828] mt-1 uppercase">{{ $website->approval_mode ?? 'Automatic' }}</p>
            </div>
            <div>
                <span class="text-[11px] text-[#667085] uppercase font-semibold">Default Interval</span>
                <p class="text-xs font-bold text-[#101828] mt-1">{{ $website->default_rewrite_interval_days ?? 30 }} {{ ucfirst($website->default_rewrite_interval_unit ?? 'days') }}</p>
            </div>
            <div>
                <span class="text-[11px] text-[#667085] uppercase font-semibold">Notification Email</span>
                <p class="text-xs font-bold text-[#15803D] mt-1 truncate" title="{{ $website->notification_email ?? 'Not Configured' }}">
                    {{ $website->notification_email ?: 'Default Admin' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-[#EAECF0] flex items-center gap-6 text-xs font-semibold">
        <button
            wire:click="$set('activeTab', 'pages')"
            class="pb-3 border-b-2 transition-colors {{ $activeTab === 'pages' ? 'border-[#22C55E] text-[#15803D]' : 'border-transparent text-[#667085] hover:text-[#101828]' }}"
        >
            Tracked Pages ({{ count($pages) }})
        </button>
        <button
            wire:click="$set('activeTab', 'history')"
            class="pb-3 border-b-2 transition-colors {{ $activeTab === 'history' ? 'border-[#22C55E] text-[#15803D]' : 'border-transparent text-[#667085] hover:text-[#101828]' }}"
        >
            Git Commit History
        </button>
        <button
            wire:click="$set('activeTab', 'rules')"
            class="pb-3 border-b-2 transition-colors {{ $activeTab === 'rules' ? 'border-[#22C55E] text-[#15803D]' : 'border-transparent text-[#667085] hover:text-[#101828]' }}"
        >
            Protected Terms & Selectors
        </button>
    </div>

    <!-- TAB 1: Pages Table -->
    @if($activeTab === 'pages')
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs overflow-hidden">
            <div class="p-4 border-b border-[#EAECF0] flex items-center justify-between gap-3">
                <div class="relative flex-1 max-w-xs">
                    <input
                        wire:model.live.debounce.300ms="searchPage"
                        type="text"
                        placeholder="Filter pages by path..."
                        class="w-full pl-9 pr-3 py-1.5 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] text-[#101828] placeholder-[#98A2B3]"
                    >
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>

                <span class="text-xs text-[#667085]">Showing {{ count($pages) }} pages</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-[#F9FAFB] border-b border-[#EAECF0] text-[#667085] font-semibold uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-4">Page Path & Name</th>
                            <th class="py-3 px-4">Words</th>
                            <th class="py-3 px-4">Last Refreshed</th>
                            <th class="py-3 px-4">Next Due</th>
                            <th class="py-3 px-4">Model</th>
                            <th class="py-3 px-4 text-right">Auto-Refresh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAECF0]">
                        @forelse($pages as $page)
                            <tr class="hover:bg-[#F9FAFB]/80 transition-colors">
                                <td class="py-3 px-4">
                                    <a href="{{ route('pages.show', $page->id) }}" class="font-mono font-medium text-[#101828] hover:text-[#15803D] block">
                                        {{ $page->path }}
                                    </a>
                                    <span class="text-[11px] text-[#667085]">{{ $page->friendly_name ?? 'Static Document' }}</span>
                                </td>
                                <td class="py-3 px-4 text-[#344054] font-medium">
                                    {{ number_format($page->word_count ?? 0) }}
                                </td>
                                <td class="py-3 px-4 text-[#667085]">
                                    {{ $page->last_rewrite_at ? $page->last_rewrite_at->diffForHumans() : 'Never' }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($page->next_rewrite_at)
                                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold border border-blue-200">
                                            {{ $page->next_rewrite_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="text-[#98A2B3]">Disabled</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                        {{ $page->ai_model ?? 'Default AI' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <button
                                        wire:click="togglePageRewrite({{ $page->id }})"
                                        type="button"
                                        class="px-2.5 py-1 text-xs rounded-lg font-semibold transition-colors {{ $page->rewrite_enabled ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}"
                                    >
                                        {{ $page->rewrite_enabled ? 'Enabled' : 'Disabled' }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 px-4 text-center">
                                    <div class="max-w-md mx-auto space-y-3">
                                        <p class="text-xs text-[#667085]">No pages tracked yet for this website.</p>
                                        <button wire:click="runAudit" type="button" class="px-4 py-2 bg-[#22C55E] hover:bg-[#16A34A] text-white font-semibold text-xs rounded-xl shadow-xs transition-all">
                                            🔍 Scan & Discover HTML Pages
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($activeTab === 'history')
        <!-- Git Commit Log -->
        <div class="bg-white rounded-2xl border border-[#EAECF0] p-6 space-y-4">
            <h3 class="text-sm font-bold text-[#101828]">Recent Git Commits</h3>
            <div class="space-y-3">
                <div class="p-3.5 rounded-xl bg-[#F9FAFB] border border-[#EAECF0] flex items-center justify-between text-xs">
                    <div class="flex items-center gap-3">
                        <span class="font-mono text-[#15803D] bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 font-bold">a9f82c4</span>
                        <div>
                            <p class="font-semibold text-[#101828]">refactor(ai): refresh /products/cloud-platform copy</p>
                            <p class="text-[11px] text-[#667085]">Committed by Autoflow Bot • 2 hours ago</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">Pushed to main</span>
                </div>
            </div>
        </div>
    @elseif($activeTab === 'rules')
        <!-- Protected Rules -->
        <div class="bg-white rounded-2xl border border-[#EAECF0] p-6 space-y-4 text-xs">
            <h3 class="text-sm font-bold text-[#101828]">Protected Brand Terms & Exclusions</h3>
            <div class="space-y-2">
                <p class="font-semibold text-[#344054]">Brand Terms:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach((array)($website->protected_terms ?? ['TechCorp', 'API', 'v2', 'OAuth']) as $term)
                        <span class="px-2.5 py-1 bg-emerald-50 text-[#15803D] rounded-lg border border-emerald-100 font-mono font-medium">{{ $term }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
