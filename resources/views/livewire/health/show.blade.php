<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#101828] tracking-tight">System Health & Infrastructure</h1>
            <p class="text-xs text-[#667085] mt-1">Live status of database clusters, worker queues, AI API gateways, and Git SSH handshakes</p>
        </div>

        <button
            wire:click="runDiagnostics"
            type="button"
            class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-colors self-start sm:self-auto flex items-center gap-2"
        >
            <svg class="w-4 h-4 {{ $isTesting ? 'animate-spin' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
            Run Full Diagnostics
        </button>
    </div>

    <!-- Health Overall Card -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center font-bold text-xl">
                ✓
            </div>
            <div>
                <h3 class="text-lg font-bold text-[#101828]">System Health & Status</h3>
                <p class="text-xs text-[#667085]">Real-time operational checks for database, AI gateways, and Git services.</p>
            </div>
        </div>
        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold self-start sm:self-auto">
            Operational
        </span>
    </div>

    <!-- Service Health Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($services as $svc)
            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-mono text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">{{ $svc->latency }}</span>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-[#101828]">{{ $svc->name }}</h4>
                    <p class="text-xs text-[#667085] mt-1">{{ $svc->detail }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
