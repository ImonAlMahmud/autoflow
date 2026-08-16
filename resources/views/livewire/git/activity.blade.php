<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#101828] tracking-tight">Git Activity Stream</h1>
            <p class="text-xs text-[#667085] mt-1">Audit log of auto-commits, remote pushes, SSH handshakes, and repository branch syncs</p>
        </div>

        <button
            wire:click="syncAllRemotes"
            type="button"
            class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-colors self-start sm:self-auto flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
            Fetch All Remotes
        </button>
    </div>

    <!-- Commits Stream List -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs overflow-hidden">
        <div class="p-4 border-b border-[#EAECF0] bg-[#F9FAFB] flex items-center justify-between">
            <h3 class="text-sm font-bold text-[#101828]">Recent Repository Commits</h3>
            <span class="text-xs text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 font-medium">All Remotes Operational</span>
        </div>

        <div class="divide-y divide-[#EAECF0]">
            @foreach($commits as $c)
                <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-[#F9FAFB]/80 transition-colors">
                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 flex items-center justify-center font-mono font-bold text-xs flex-shrink-0">
                            git
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ $c->hash }}</span>
                                <span class="text-xs font-semibold text-[#101828]">{{ $c->message }}</span>
                            </div>

                            <p class="text-xs text-[#667085]">
                                Website: <span class="font-medium text-[#101828]">{{ $c->website }}</span> • Branch: <code class="font-mono text-indigo-600">{{ $c->branch }}</code> • Author: {{ $c->author }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-xs self-end sm:self-auto">
                        <div class="text-right">
                            <div class="font-mono text-[11px]">
                                <span class="text-emerald-600 font-semibold">+{{ $c->additions }}</span>
                                <span class="text-rose-600 font-semibold ml-1">-{{ $c->deletions }}</span>
                            </div>
                            <span class="text-[10px] text-[#98A2B3] block">{{ $c->time }}</span>
                        </div>

                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold text-[11px]">
                            Pushed ✓
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
