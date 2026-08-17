<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#101828] tracking-tight">Audit & System Logs</h1>
            <p class="text-xs text-[#667085] mt-1">Real-time system diagnostics, execution trace, and security audit history</p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto">
            <button
                wire:click="exportLogs"
                type="button"
                class="px-3.5 py-2 rounded-xl border border-[#D0D5DD] bg-white hover:bg-[#F9FAFB] text-xs font-semibold text-[#344054] transition-colors shadow-xs"
            >
                Export JSON
            </button>
            <button
                wire:click="clearLogs"
                type="button"
                class="px-3.5 py-2 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-semibold transition-colors"
            >
                Clear History
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-[#EAECF0] shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="relative flex-1 max-w-md">
            <i class="fa-solid fa-magnifying-glass text-[#98A2B3] absolute left-3.5 top-3 text-xs"></i>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Filter logs by message or module..."
                class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] placeholder-[#98A2B3]"
            >
        </div>

        <div class="flex items-center gap-1.5 p-1 bg-[#F2F4F7] rounded-xl border border-[#EAECF0] text-xs">
            <button wire:click="$set('levelFilter', 'all')" class="px-3 py-1.5 font-medium rounded-lg {{ $levelFilter === 'all' ? 'bg-white text-[#15803D] font-semibold shadow-xs' : 'text-[#667085]' }}">All</button>
            <button wire:click="$set('levelFilter', 'info')" class="px-3 py-1.5 font-medium rounded-lg {{ $levelFilter === 'info' ? 'bg-white text-blue-600 font-semibold shadow-xs' : 'text-[#667085]' }}">Info</button>
            <button wire:click="$set('levelFilter', 'warning')" class="px-3 py-1.5 font-medium rounded-lg {{ $levelFilter === 'warning' ? 'bg-white text-amber-700 font-semibold shadow-xs' : 'text-[#667085]' }}">Warning</button>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-[#F9FAFB] border-b border-[#EAECF0] text-[#667085] font-semibold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4">Timestamp</th>
                        <th class="py-3 px-4">Level</th>
                        <th class="py-3 px-4">Module</th>
                        <th class="py-3 px-4">Event Payload / Message</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAECF0]">
                    @foreach($logs as $log)
                        <tr class="hover:bg-[#F9FAFB]/80 transition-colors font-mono">
                            <td class="py-3.5 px-4 text-[#667085] text-[11px]">
                                {{ $log->created_at->diffForHumans() }}
                            </td>
                            <td class="py-3.5 px-4 font-sans">
                                @if($log->level === 'info')
                                    <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 font-semibold text-[10px] uppercase">INFO</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-800 border border-amber-200 font-semibold text-[10px] uppercase">WARN</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-sans font-bold text-[#101828]">
                                {{ $log->module }}
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="text-[#101828] font-sans font-medium text-xs">{{ $log->message }}</p>
                                <code class="text-[10px] text-[#667085] block mt-0.5">{{ $log->context }}</code>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
