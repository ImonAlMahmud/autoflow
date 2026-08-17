<x-app-layout>
    <div class="space-y-6">
        <!-- Dashboard Top Header Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-[#EAECF0] shadow-xs">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#15803D] border border-emerald-100">
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
                    <i class="fa-solid fa-globe text-[#667085] text-xs"></i>
                    Manage Websites
                </a>

                <a
                    href="{{ route('jobs.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#22C55E] hover:bg-[#16A34A] text-white text-sm font-semibold transition-all shadow-sm hover:shadow-md"
                >
                    <i class="fa-solid fa-bolt text-white text-xs"></i>
                    Trigger Quick Run
                </a>
            </div>
        </div>

        <!-- Livewire Overview Component -->
        <livewire:dashboard.overview />
    </div>
</x-app-layout>
