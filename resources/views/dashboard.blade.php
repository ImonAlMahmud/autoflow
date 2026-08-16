<x-app-layout>
    <div class="space-y-6">
        <!-- Dashboard Top Header Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-[#EAECF0] shadow-xs">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                        Live System Monitor
                    </span>
                    <span class="text-xs text-[#667085] font-medium">
                        {{ now()->format('l, F j, Y') }}
                    </span>
                </div>
                <h1 class="text-2xl font-bold text-[#101828] tracking-tight mt-1">Dashboard Overview</h1>
                <p class="text-sm text-[#667085] mt-0.5">
                    Real-time automated static website content refreshes, AI pipeline status, and git synchronization.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex items-center gap-3 flex-wrap">
                <a
                    href="{{ route('websites.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-[#D0D5DD] bg-white text-sm font-semibold text-[#344054] hover:bg-[#F9FAFB] hover:border-[#98A2B3] transition-all shadow-xs"
                >
                    <svg class="w-4 h-4 text-[#667085]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                    Manage Websites
                </a>

                <a
                    href="{{ route('jobs.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition-all shadow-sm hover:shadow-md"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Trigger Quick Run
                </a>
            </div>
        </div>

        <!-- Livewire Overview Component -->
        <livewire:dashboard.overview />
    </div>
</x-app-layout>
