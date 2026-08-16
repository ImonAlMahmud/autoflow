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

    <!-- Scripts & Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
    class="h-full font-sans antialiased text-[#101828] bg-[#F7F9FC] selection:bg-indigo-500 selection:text-white"
>
    <!-- GLOBAL FULL-PAGE BLURRED BACKDROP SPINNER LOADER -->
    <div wire:loading.delay.longer class="fixed inset-0 z-[99999] flex items-center justify-center bg-slate-950/60 backdrop-blur-md transition-all">
        <div class="bg-white rounded-3xl p-8 shadow-2xl border border-slate-100 flex flex-col items-center justify-center space-y-5 max-w-sm w-full mx-4 text-center">
            <!-- Modern Dual-Ring Glowing Spinner -->
            <div class="relative w-20 h-20 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border-4 border-indigo-100"></div>
                <div class="absolute inset-0 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin"></div>
                <div class="absolute inset-2 rounded-full border-4 border-purple-500 border-b-transparent animate-spin [animation-duration:1.5s]"></div>
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shadow-xs">
                    <svg class="w-5 h-5 text-indigo-600 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            
            <div class="space-y-1">
                <h3 class="text-base font-extrabold text-[#101828] tracking-tight">AI Processing & Git Push</h3>
                <p class="text-xs text-[#667085] leading-relaxed">Executing text rewrite, preserving UI layout, and committing to GitHub...</p>
            </div>

            <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full border border-indigo-200">
                <span class="w-2 h-2 rounded-full bg-indigo-600 animate-ping"></span>
                Processing live request...
            </div>
        </div>
    </div>

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
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Search Input Trigger (Ctrl+K) -->
                    <button
                        @click="searchOpen = true"
                        type="button"
                        class="flex items-center gap-3 px-3.5 py-2 w-64 sm:w-80 rounded-lg border border-[#D0D5DD] bg-[#F9FAFB] hover:bg-white hover:border-indigo-300 text-sm text-[#667085] transition-all shadow-xs group focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                        <svg class="w-4 h-4 text-[#98A2B3] group-hover:text-indigo-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
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
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <!-- Notification Counter Dot -->
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-indigo-600 ring-2 ring-white"></span>
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
                                <a href="{{ route('logs.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View activity logs →</a>
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
                            <svg class="w-4 h-4 text-[#98A2B3] hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
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
                                <svg class="w-4 h-4 text-[#98A2B3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                System Settings
                            </a>
                            <a href="{{ route('ai.models') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-[#344054] hover:bg-[#F9FAFB] hover:text-[#101828]">
                                <svg class="w-4 h-4 text-[#98A2B3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                API Tokens & Keys
                            </a>
                            <div class="border-t border-[#EAECF0] my-1"></div>
                            <form method="POST" action="{{ route('logout') ?? '#' }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-medium">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
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
                    'border-indigo-200 text-indigo-950': t.type === 'info'
                }"
            >
                <div class="flex-shrink-0 mt-0.5">
                    <template x-if="t.type === 'success'">
                        <span class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                    </template>
                    <template x-if="t.type === 'warning'">
                        <span class="w-7 h-7 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </span>
                    </template>
                    <template x-if="t.type === 'danger'">
                        <span class="w-7 h-7 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </span>
                    </template>
                    <template x-if="t.type === 'info'">
                        <span class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                    </template>
                </div>
                <div class="flex-1 pr-4">
                    <h4 class="text-xs font-semibold text-[#101828]" x-text="t.title"></h4>
                    <p class="text-xs text-[#667085] mt-0.5" x-show="t.message" x-text="t.message"></p>
                </div>
                <button @click="removeToast(t.id)" class="text-[#98A2B3] hover:text-[#101828] p-1 rounded-md">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
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
                <svg class="w-5 h-5 text-[#98A2B3] mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
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
                        <a href="{{ route('dashboard') }}" @click="searchOpen = false" class="p-2.5 rounded-lg border border-[#EAECF0] hover:border-indigo-300 hover:bg-indigo-50/50 flex items-center gap-3 group transition-colors">
                            <span class="p-2 rounded-md bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                            </span>
                            <div>
                                <p class="text-xs font-semibold text-[#101828]">Dashboard Overview</p>
                                <p class="text-[10px] text-[#667085]">Metrics & Live Activity</p>
                            </div>
                        </a>
                        <a href="{{ route('websites.index') }}" @click="searchOpen = false" class="p-2.5 rounded-lg border border-[#EAECF0] hover:border-indigo-300 hover:bg-indigo-50/50 flex items-center gap-3 group transition-colors">
                            <span class="p-2 rounded-md bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                            </span>
                            <div>
                                <p class="text-xs font-semibold text-[#101828]">Websites List</p>
                                <p class="text-[10px] text-[#667085]">Git repositories connected</p>
                            </div>
                        </a>
                        <a href="{{ route('ai.models') }}" @click="searchOpen = false" class="p-2.5 rounded-lg border border-[#EAECF0] hover:border-indigo-300 hover:bg-indigo-50/50 flex items-center gap-3 group transition-colors">
                            <span class="p-2 rounded-md bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </span>
                            <div>
                                <p class="text-xs font-semibold text-[#101828]">AI Models & Prompts</p>
                                <p class="text-[10px] text-[#667085]">Manage LLMs & Templates</p>
                            </div>
                        </a>
                        <a href="{{ route('jobs.index') }}" @click="searchOpen = false" class="p-2.5 rounded-lg border border-[#EAECF0] hover:border-indigo-300 hover:bg-indigo-50/50 flex items-center gap-3 group transition-colors">
                            <span class="p-2 rounded-md bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
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

    <!-- Global Livewire Processing Spinner Overlay (Background Blur) -->
    <div wire:loading.delay.short class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-slate-900/30 backdrop-blur-md transition-all">
        <div class="bg-white/95 rounded-2xl p-6 shadow-2xl border border-indigo-100 flex flex-col items-center gap-3.5 max-w-xs text-center transform animate-in fade-in zoom-in-95 duration-200">
            <!-- Sleek Gradient Spinner -->
            <div class="relative w-12 h-12 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full border-4 border-indigo-100"></div>
                <div class="absolute inset-0 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin"></div>
                <svg class="w-5 h-5 text-indigo-600 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-[#101828]">Processing Request...</p>
                <p class="text-xs text-[#667085] mt-0.5">Performing action & syncing live state</p>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
