<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F7F9FC]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Autoflow') }} - AI Website Refresh System</title>

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Chart.js for High Performance Visual Dashboards -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body
    x-data="{
        sidebarOpen: true,
        mobileSidebarOpen: false,
        searchOpen: false,
        notificationsOpen: false,
        userMenuOpen: false,
        toasts: [],
        addToast(title, message = '', type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, title, message, type });
            setTimeout(() => { this.removeToast(id); }, 5000);
        },
        removeToast(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    @keydown.window.prevent.cmd.k="searchOpen = true"
    @keydown.window.prevent.ctrl.k="searchOpen = true"
    @toast.window="addToast($event.detail.title, $event.detail.message, $event.detail.type)"
    class="h-full font-sans antialiased text-[#101828] bg-[#F7F9FC] selection:bg-emerald-500 selection:text-white"
>

    <div class="min-h-screen flex flex-col md:flex-row bg-[#F7F9FC]">
        
        <!-- Mobile Sidebar Overlay Backdrop -->
        <div
            x-cloak
            x-show="mobileSidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="mobileSidebarOpen = false"
            class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-xs md:hidden"
        ></div>

        <!-- Mobile Sidebar Drawer -->
        <div
            x-cloak
            x-show="mobileSidebarOpen"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white md:hidden shadow-xl"
        >
            @include('layouts.navigation')
        </div>

        <!-- Desktop Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            @include('layouts.navigation')
        </div>

        <!-- Main Workspace Container -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Bar Header -->
            <header class="sticky top-0 z-20 bg-white/90 backdrop-blur-md border-b border-[#EAECF0] h-16 flex items-center justify-between px-4 sm:px-6 shadow-xs">
                
                <!-- Left: Mobile Menu Button & Search Trigger -->
                <div class="flex items-center gap-3">
                    <!-- Mobile Hamburger -->
                    <button
                        @click="mobileSidebarOpen = !mobileSidebarOpen"
                        type="button"
                        class="md:hidden p-2 rounded-lg text-[#667085] hover:text-[#101828] hover:bg-[#F3F4F6] focus:outline-none"
                    >
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>

                    <!-- Search Input Trigger (Ctrl+K) -->
                    <button
                        @click="searchOpen = true"
                        type="button"
                        class="flex items-center gap-3 px-3.5 py-2 w-64 sm:w-80 rounded-lg border border-[#D0D5DD] bg-[#F9FAFB] hover:bg-white hover:border-indigo-300 text-sm text-[#667085] transition-all shadow-xs group focus:outline-none focus:ring-2 focus:ring-green-500/20"
                    >
                        <i class="fa-solid fa-magnifying-glass text-[#98A2B3] group-hover:text-[#15803D] transition-colors text-xs"></i>
                        <span class="flex-1 text-left truncate text-xs sm:text-sm">Search websites, jobs, models...</span>
                        <kbd class="hidden sm:inline-flex items-center gap-0.5 px-2 py-0.5 text-[11px] font-mono font-medium text-[#475467] bg-white border border-[#D0D5DD] rounded shadow-xs">
                            Ctrl K
                        </kbd>
                    </button>
                </div>

                <!-- Right: Status Pill, Quick Actions, Notifications & Profile -->
                <div class="flex items-center gap-2 sm:gap-4">
                    
                    <!-- Live System Status Badge -->
                    <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        AI Engine: Operational
                    </div>

                    <!-- Notifications Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            type="button"
                            class="relative p-2 rounded-lg text-[#667085] hover:text-[#101828] hover:bg-[#F3F4F6] transition-colors focus:outline-none"
                            title="Notifications"
                        >
                            <i class="fa-solid fa-bell text-sm"></i>
                            <!-- Notification Counter Dot -->
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-[#22C55E] ring-2 ring-white"></span>
                        </button>

                        <!-- Notification Panel Popover -->
                        <div
                            x-cloak
                            x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-xl shadow-modal border border-[#EAECF0] py-2 z-50 overflow-hidden"
                        >
                            <div class="px-4 py-3 border-b border-[#EAECF0] flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-[#101828]">System Notifications</h3>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-medium">0 unread</span>
                            </div>
                            <div class="divide-y divide-[#EAECF0] max-h-80 overflow-y-auto p-4 text-center">
                                <p class="text-xs text-[#98A2B3]">No new notifications at this time.</p>
                            </div>
                            <div class="px-4 py-2 border-t border-[#EAECF0] bg-[#F9FAFB] text-center">
                                <a href="{{ route('logs.index') }}" class="text-xs font-semibold text-[#15803D] hover:text-[#15803D]">View activity logs →</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            type="button"
                            class="flex items-center gap-2.5 p-1.5 rounded-lg hover:bg-[#F3F4F6] transition-colors focus:outline-none"
                        >
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 text-white font-semibold text-xs flex items-center justify-center shadow-xs ring-2 ring-white">
                                {{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 2)) }}
                            </div>
                            <div class="hidden sm:flex flex-col text-left">
                                <span class="text-xs font-semibold text-[#101828] leading-snug">{{ auth()->user()->name ?? 'System Admin' }}</span>
                                <span class="text-[10px] text-[#667085] leading-none">{{ auth()->user()->email ?? 'admin@autoflow.io' }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-xs text-[#98A2B3] hidden sm:block"></i>
                        </button>

                        <div
                            x-cloak
                            x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-modal border border-[#EAECF0] py-1 z-50"
                        >
                            <div class="px-4 py-2.5 border-b border-[#EAECF0]">
                                <p class="text-xs font-semibold text-[#101828]">{{ auth()->user()->name ?? 'Administrator' }}</p>
                                <p class="text-xs text-[#667085] truncate">{{ auth()->user()->email ?? 'admin@autoflow.io' }}</p>
                            </div>
                            <a href="{{ route('settings') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-[#344054] hover:bg-[#F9FAFB] hover:text-[#101828]">
                                <i class="fa-solid fa-sliders text-[#98A2B3] w-4 text-center"></i>
                                System Settings
                            </a>
                            <a href="{{ route('ai.models') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-[#344054] hover:bg-[#F9FAFB] hover:text-[#101828]">
                                <i class="fa-solid fa-key text-[#98A2B3] w-4 text-center"></i>
                                API Tokens & Keys
                            </a>
                            <div class="border-t border-[#EAECF0] my-1"></div>
                            <form method="POST" action="{{ route('logout') ?? '#' }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-medium">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-rose-500 w-4 text-center"></i>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Page Content Body -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-[#F7F9FC]">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Global Toast Notifications Stack Container -->
    <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none">
        <template x-for="t in toasts" :key="t.id">
            <div
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                class="pointer-events-auto p-4 rounded-xl bg-white border shadow-modal flex items-start gap-3 relative overflow-hidden"
                :class="{
                    'border-emerald-200 text-emerald-950': t.type === 'success',
                    'border-amber-200 text-amber-950': t.type === 'warning',
                    'border-rose-200 text-rose-950': t.type === 'danger',
                    'border-emerald-200 text-indigo-950': t.type === 'info'
                }"
            >
                <div class="flex-shrink-0 mt-0.5">
                    <template x-if="t.type === 'success'">
                        <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-check text-xs"></i>
                        </span>
                    </template>
                    <template x-if="t.type === 'warning'">
                        <span class="w-7 h-7 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                            <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                        </span>
                    </template>
                    <template x-if="t.type === 'danger'">
                        <span class="w-7 h-7 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                            <i class="fa-solid fa-circle-xmark text-xs"></i>
                        </span>
                    </template>
                    <template x-if="t.type === 'info'">
                        <span class="w-7 h-7 rounded-full bg-emerald-100 text-[#15803D] flex items-center justify-center">
                            <i class="fa-solid fa-circle-info text-xs"></i>
                        </span>
                    </template>
                </div>
                <div class="flex-1 pr-4">
                    <h4 class="text-xs font-semibold text-[#101828]" x-text="t.title"></h4>
                    <p class="text-xs text-[#667085] mt-0.5" x-show="t.message" x-text="t.message"></p>
                </div>
                <button @click="removeToast(t.id)" class="text-[#98A2B3] hover:text-[#101828] p-1 rounded-md">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
        </template>
    </div>

    <!-- Search Modal / Command Palette (Ctrl+K) -->
    <div
        x-cloak
        x-show="searchOpen"
        @keydown.escape.window="searchOpen = false"
        class="fixed inset-0 z-50 flex items-start justify-center pt-20 px-4"
    >
        <div
            x-show="searchOpen"
            x-transition:enter="transition opacity ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition opacity ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="searchOpen = false"
            class="fixed inset-0 bg-gray-900/40 backdrop-blur-xs"
        ></div>

        <div
            x-show="searchOpen"
            x-transition:enter="transition ease-out duration-200 transform"
            x-transition:enter-start="opacity-0 scale-95 -translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150 transform"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 -translate-y-4"
            class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-[#EAECF0] overflow-hidden z-10"
        >
            <div class="flex items-center px-4 border-b border-[#EAECF0]">
                <i class="fa-solid fa-magnifying-glass text-[#98A2B3] mr-3 text-sm"></i>
                <input
                    type="text"
                    placeholder="Search websites, pages, AI models, automation jobs..."
                    class="w-full py-4 text-sm bg-transparent border-0 focus:ring-0 text-[#101828] placeholder-[#98A2B3]"
                    x-init="$watch('searchOpen', val => val && $nextTick(() => $el.focus()))"
                >
                <kbd class="px-2 py-1 text-[10px] font-mono text-[#667085] bg-[#F2F4F7] rounded border border-[#EAECF0]">ESC</kbd>
            </div>

            <!-- Quick Navigation Suggestions -->
            <div class="p-4 max-h-96 overflow-y-auto space-y-4">
                <div>
                    <div class="text-[11px] font-semibold text-[#98A2B3] uppercase tracking-wider mb-2">Quick Navigation</div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('dashboard') }}" @click="searchOpen = false" class="p-2.5 rounded-lg border border-[#EAECF0] hover:border-indigo-300 hover:bg-emerald-50/50 flex items-center gap-3 group transition-colors">
                            <span class="p-2 rounded-md bg-emerald-50 text-[#15803D] group-hover:bg-[#22C55E] group-hover:text-white transition-colors">
                                <i class="fa-solid fa-gauge-high text-sm"></i>
                            </span>
                            <div>
                                <p class="text-xs font-semibold text-[#101828]">Dashboard Overview</p>
                                <p class="text-[10px] text-[#667085]">Metrics & Live Activity</p>
                            </div>
                        </a>
                        <a href="{{ route('websites.index') }}" @click="searchOpen = false" class="p-2.5 rounded-lg border border-[#EAECF0] hover:border-indigo-300 hover:bg-emerald-50/50 flex items-center gap-3 group transition-colors">
                            <span class="p-2 rounded-md bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-globe text-sm"></i>
                            </span>
                            <div>
                                <p class="text-xs font-semibold text-[#101828]">Websites List</p>
                                <p class="text-[10px] text-[#667085]">Git repositories connected</p>
                            </div>
                        </a>
                        <a href="{{ route('ai.models') }}" @click="searchOpen = false" class="p-2.5 rounded-lg border border-[#EAECF0] hover:border-indigo-300 hover:bg-emerald-50/50 flex items-center gap-3 group transition-colors">
                            <span class="p-2 rounded-md bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-brain text-sm"></i>
                            </span>
                            <div>
                                <p class="text-xs font-semibold text-[#101828]">AI Models & Prompts</p>
                                <p class="text-[10px] text-[#667085]">Manage LLMs & Templates</p>
                            </div>
                        </a>
                        <a href="{{ route('jobs.index') }}" @click="searchOpen = false" class="p-2.5 rounded-lg border border-[#EAECF0] hover:border-indigo-300 hover:bg-emerald-50/50 flex items-center gap-3 group transition-colors">
                            <span class="p-2 rounded-md bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-gears text-sm"></i>
                            </span>
                            <div>
                                <p class="text-xs font-semibold text-[#101828]">Automation Jobs</p>
                                <p class="text-[10px] text-[#667085]">Active & completed tasks</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="px-4 py-2.5 bg-[#F9FAFB] border-t border-[#EAECF0] flex items-center justify-between text-[11px] text-[#667085]">
                <span>Tip: Press <kbd class="px-1.5 py-0.5 bg-white border border-[#D0D5DD] rounded font-mono text-[10px]">Tab</kbd> to cycle results</span>
                <span>Autoflow Quick Search</span>
            </div>
        </div>
    </div>

    <!-- Global Subscription Required Paywall Modal -->
    @if(auth()->check() && !auth()->user()->hasActiveSubscription())
    <div
        x-data="{ showPaywall: false, featureName: 'this feature' }"
        @open-paywall.window="showPaywall = true; featureName = $event.detail.feature || 'this automation feature'"
        x-cloak
        x-show="showPaywall"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md animate-in fade-in"
    >
        <div
            @click.away="showPaywall = false"
            class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 text-center space-y-6 shadow-2xl border border-gray-100 relative overflow-hidden"
        >
            <!-- Decorative Badge -->
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center mx-auto text-2xl shadow-inner">
                <i class="fa-solid fa-lock"></i>
            </div>

            <div class="space-y-2">
                <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-extrabold uppercase tracking-wider">
                    Subscription Required ⚡
                </span>
                <h3 class="text-xl font-extrabold text-[#0F172A]">
                    Upgrade Plan to Unlock Access
                </h3>
                <p class="text-xs text-[#64748B] leading-relaxed">
                    You need an active subscription to access <span class="font-bold text-[#0F172A]" x-text="featureName"></span> and execute autonomous AI website content refreshes.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-[#F8FAFC] border border-[#E2E8F0] text-left space-y-2 text-xs">
                <div class="font-bold text-[#0F172A] flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-check text-[#22C55E]"></i>
                    Included in All SaaS Plans:
                </div>
                <ul class="text-[11px] text-[#64748B] space-y-1 pl-5 list-disc">
                    <li>AI Content Rewriting (Cloud LLMs & Local Ollama)</li>
                    <li>DOM Safe Layout & CSS Preservation Guarantee</li>
                    <li>Autonomous Git Sync & Automated Commits</li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                <button
                    @click="showPaywall = false"
                    type="button"
                    class="w-full sm:w-1/2 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-[#64748B] hover:bg-gray-50 transition-colors"
                >
                    Maybe Later
                </button>

                <a
                    href="{{ route('subscription') }}"
                    class="w-full sm:w-1/2 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs shadow-lg shadow-green-500/20 transition-all hover:scale-[1.02] flex items-center justify-center gap-2"
                >
                    <i class="fa-solid fa-gem text-xs"></i>
                    <span>Choose Plan →</span>
                </a>
            </div>
        </div>
    </div>
    @endif

    @livewireScripts
</body>
</html>
