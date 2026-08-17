<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300 flex items-center gap-1">
                    <i class="fa-solid fa-crown text-amber-600 text-[10px]"></i> Super Admin Control
                </span>
                <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">All Users' Connected Websites</h1>
            </div>
            <p class="text-xs text-[#64748B] mt-1">Master directory of all websites connected across all client accounts with real-time execution status and last job history.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-white border border-[#CBD5E1] text-xs font-bold text-[#334155] shadow-xs">
                Total Sites: <strong class="text-[#15803D]">{{ $websites->total() }}</strong>
            </span>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-card p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="relative flex-1 max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-[#94A3B8]"></i>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search by site name, domain, repo, or user email..."
                class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-[#CBD5E1] bg-[#F8FAFC] focus:bg-white text-[#0F172A] focus:ring-2 focus:ring-[#22C55E] transition-all"
            >
        </div>

        <div class="flex items-center gap-2">
            <select
                wire:model.live="statusFilter"
                class="px-3 py-2 text-xs rounded-xl border border-[#CBD5E1] bg-white text-[#0F172A] font-medium focus:ring-2 focus:ring-[#22C55E]"
            >
                <option value="all">All Statuses</option>
                <option value="active">Active</option>
                <option value="paused">Paused</option>
            </select>
        </div>
    </div>

    <!-- Websites Table Card -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#334155]">
                <thead class="bg-[#F8FAFC] text-[11px] font-bold uppercase tracking-wider text-[#64748B] border-b border-[#E2E8F0]">
                    <tr>
                        <th class="px-5 py-3.5">Website & Domain</th>
                        <th class="px-5 py-3.5">Owner / User</th>
                        <th class="px-5 py-3.5">GitHub Repository</th>
                        <th class="px-5 py-3.5">Pages & Interval</th>
                        <th class="px-5 py-3.5">Last Job History</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($websites as $site)
                        <tr class="hover:bg-[#F8FAFC]/80 transition-colors">
                            <!-- Website Name & Domain -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-[#0F172A] text-sm flex items-center gap-1.5">
                                    {{ $site->name }}
                                </div>
                                <div class="text-[11px] text-[#64748B] flex items-center gap-1 mt-0.5">
                                    <i class="fa-solid fa-globe text-[10px]"></i>
                                    <a href="https://{{ ltrim($site->domain, 'https://') }}" target="_blank" class="hover:underline hover:text-[#15803D]">
                                        {{ $site->domain }}
                                    </a>
                                </div>
                            </td>

                            <!-- Owner / User -->
                            <td class="px-5 py-4">
                                @if($site->user)
                                    <div class="font-semibold text-[#0F172A] flex items-center gap-1.5">
                                        <div class="w-5 h-5 rounded-full bg-emerald-100 text-[#15803D] font-bold text-[10px] flex items-center justify-center">
                                            {{ strtoupper(substr($site->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span>{{ $site->user->name }}</span>
                                        @if($site->user->isSuperAdmin())
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-amber-100 text-amber-900 border border-amber-300">ADMIN</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-[#64748B] font-mono mt-0.5">{{ $site->user->email }}</div>
                                @else
                                    <span class="text-[11px] text-gray-400 italic">System / Unassigned</span>
                                @endif
                            </td>

                            <!-- GitHub Repo & Branch -->
                            <td class="px-5 py-4">
                                <div class="font-mono text-xs text-[#0F172A] flex items-center gap-1 truncate max-w-[220px]" title="{{ $site->git_repository_url }}">
                                    <i class="fa-brands fa-github text-sm text-[#0F172A]"></i>
                                    {{ str_replace(['https://github.com/', '.git'], '', $site->git_repository_url) }}
                                </div>
                                <div class="text-[10px] text-[#64748B] font-mono mt-0.5">
                                    Branch: <span class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-700 font-bold">{{ $site->git_branch ?: 'main' }}</span>
                                </div>
                            </td>

                            <!-- Pages & Frequency -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-[#0F172A]">
                                    {{ $site->pages_count }} Pages
                                </div>
                                <div class="text-[11px] text-[#64748B] mt-0.5">
                                    Every {{ $site->default_rewrite_interval_days ?? 5 }} {{ $site->default_rewrite_interval_unit ?? 'minutes' }}
                                </div>
                            </td>

                            <!-- Last Job History -->
                            <td class="px-5 py-4">
                                @if($site->last_job)
                                    @php
                                        $jobStatus = is_object($site->last_job->status) ? $site->last_job->status->value : (string)$site->last_job->status;
                                    @endphp
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5">
                                            @if($jobStatus === 'completed')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-[#15803D] border border-emerald-200">
                                                    ✓ #{{ $site->last_job->id }} Pushed
                                                </span>
                                            @elseif($jobStatus === 'pending_approval')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                    ⏳ #{{ $site->last_job->id }} Pending Review
                                                </span>
                                            @elseif($jobStatus === 'failed')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                    ✕ #{{ $site->last_job->id }} Failed
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                    #{{ $site->last_job->id }} {{ ucfirst($jobStatus) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-[#64748B]">
                                            {{ $site->last_job->updated_at->diffForHumans() }}
                                        </div>
                                    </div>
                                @else
                                    <span class="text-[11px] text-gray-400 italic">No runs yet</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4">
                                @php
                                    $st = is_object($site->status) ? $site->status->value : (string)$site->status;
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold inline-flex items-center gap-1 {{ $st === 'active' ? 'bg-emerald-50 text-[#15803D] border border-emerald-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $st === 'active' ? 'bg-[#22C55E]' : 'bg-gray-400' }}"></span>
                                    {{ ucfirst($st) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right space-x-1.5">
                                <a
                                    href="{{ route('websites.show', $site->id) }}"
                                    class="px-2.5 py-1.5 rounded-lg border border-[#CBD5E1] bg-white hover:bg-[#F8FAFC] text-[#15803D] font-bold text-xs transition-all shadow-xs inline-flex items-center gap-1"
                                    title="View Website Details & Pages"
                                >
                                    <span>View</span>
                                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-xs text-[#64748B]">
                                <i class="fa-solid fa-globe text-3xl text-[#CBD5E1] mb-2 block"></i>
                                No connected websites found across the system.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($websites->hasPages())
            <div class="p-4 border-t border-[#E2E8F0] bg-[#F8FAFC]">
                {{ $websites->links() }}
            </div>
        @endif
    </div>
</div>
