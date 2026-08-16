<aside
    x-cloak
    :class="sidebarOpen ? 'w-64' : 'w-20'"
    class="relative z-30 flex flex-col flex-shrink-0 bg-white border-r border-[#EAECF0] transition-all duration-300 ease-in-out select-none shadow-sidebar min-h-screen"
>
    <!-- Brand Header -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-[#EAECF0]">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 via-indigo-600 to-indigo-700 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                <!-- Autoflow Workflow Icon -->
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-[-10px]" x-transition:enter-end="opacity-100 translate-x-0" class="flex flex-col">
                <span class="font-bold text-lg text-[#101828] tracking-tight leading-none flex items-center gap-1.5">
                    Autoflow
                    <span class="text-[10px] font-semibold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded-full border border-indigo-100">v1.0</span>
                </span>
                <span class="text-xs text-[#667085] font-normal tracking-normal mt-0.5">AI Website Auto-Refresh</span>
            </div>
        </a>

        <!-- Collapse Sidebar Toggle (Desktop) -->
        <button
            @click="sidebarOpen = !sidebarOpen"
            type="button"
            class="hidden md:flex items-center justify-center w-8 h-8 rounded-lg text-[#667085] hover:text-[#101828] hover:bg-[#F3F4F6] transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
            :title="sidebarOpen ? 'Collapse sidebar' : 'Expand sidebar'"
        >
            <svg class="w-5 h-5 transition-transform duration-300" :class="{ 'rotate-180': !sidebarOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    <!-- Navigation Scroll Container -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
        
        <!-- SECTION 1: MAIN NAVIGATION -->
        <div>
            <div x-show="sidebarOpen" class="px-3 mb-2 text-[11px] font-semibold tracking-wider text-[#98A2B3] uppercase">
                Overview
            </div>
            <nav class="space-y-1">
                <!-- Dashboard -->
                <a
                    href="{{ route('dashboard') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-xs' : 'text-[#667085] hover:text-[#101828] hover:bg-[#F9FAFB]' }}"
                    :title="!sidebarOpen ? 'Dashboard' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-[#98A2B3] group-hover:text-[#475467]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Dashboard</span>
                    @if(request()->routeIs('dashboard'))
                        <span class="absolute right-2 w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                    @endif
                </a>

                <!-- Websites -->
                <a
                    href="{{ route('websites.index') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('websites.*') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-xs' : 'text-[#667085] hover:text-[#101828] hover:bg-[#F9FAFB]' }}"
                    :title="!sidebarOpen ? 'Websites' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('websites.*') ? 'text-indigo-600' : 'text-[#98A2B3] group-hover:text-[#475467]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                    <span x-show="sidebarOpen" class="truncate flex-1">Websites</span>
                </a>

                <!-- Content Pages -->
                <a
                    href="{{ route('pages.index') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('pages.*') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-xs' : 'text-[#667085] hover:text-[#101828] hover:bg-[#F9FAFB]' }}"
                    :title="!sidebarOpen ? 'Pages' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('pages.*') ? 'text-indigo-600' : 'text-[#98A2B3] group-hover:text-[#475467]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="sidebarOpen" class="truncate flex-1">Pages</span>
                </a>
            </nav>
        </div>

        <!-- SECTION 2: AI & AUTOMATION -->
        <div>
            <div x-show="sidebarOpen" class="px-3 mb-2 text-[11px] font-semibold tracking-wider text-[#98A2B3] uppercase">
                AI & Automation
            </div>
            <nav class="space-y-1">
                <!-- AI Models & Prompts -->
                <a
                    href="{{ route('ai.models') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('ai.*') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-xs' : 'text-[#667085] hover:text-[#101828] hover:bg-[#F9FAFB]' }}"
                    :title="!sidebarOpen ? 'AI Models' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('ai.*') ? 'text-indigo-600' : 'text-[#98A2B3] group-hover:text-[#475467]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <span x-show="sidebarOpen" class="truncate flex-1">AI Models</span>
                </a>

                <!-- Automation / Jobs -->
                <a
                    href="{{ route('jobs.index') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('jobs.*') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-xs' : 'text-[#667085] hover:text-[#101828] hover:bg-[#F9FAFB]' }}"
                    :title="!sidebarOpen ? 'Automation Jobs' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('jobs.*') ? 'text-indigo-600' : 'text-[#98A2B3] group-hover:text-[#475467]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span x-show="sidebarOpen" class="truncate flex-1">Automation Jobs</span>
                </a>
            </nav>
        </div>

        <!-- SECTION 3: SYSTEM & REPOSITORY -->
        <div>
            <div x-show="sidebarOpen" class="px-3 mb-2 text-[11px] font-semibold tracking-wider text-[#98A2B3] uppercase">
                System & Settings
            </div>
            <nav class="space-y-1">
                <!-- Git Activity -->
                <a
                    href="{{ route('git.activity') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('git.*') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-xs' : 'text-[#667085] hover:text-[#101828] hover:bg-[#F9FAFB]' }}"
                    :title="!sidebarOpen ? 'Git Sync' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('git.*') ? 'text-indigo-600' : 'text-[#98A2B3] group-hover:text-[#475467]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Git Sync & Activity</span>
                </a>

                <!-- System Health -->
                <a
                    href="{{ route('system-health') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('system-health') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-xs' : 'text-[#667085] hover:text-[#101828] hover:bg-[#F9FAFB]' }}"
                    :title="!sidebarOpen ? 'System Health' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('system-health') ? 'text-indigo-600' : 'text-[#98A2B3] group-hover:text-[#475467]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">System Health</span>
                </a>

                <!-- Linux Deployment Guide -->
                <a
                    href="{{ route('system.deployment') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('system.deployment') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-xs' : 'text-[#667085] hover:text-[#101828] hover:bg-[#F9FAFB]' }}"
                    :title="!sidebarOpen ? 'Linux Setup' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('system.deployment') ? 'text-indigo-600' : 'text-[#98A2B3] group-hover:text-[#475467]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Linux Server Setup 🐧</span>
                </a>

                <!-- Settings -->
                <a
                    href="{{ route('settings') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('settings') ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-xs' : 'text-[#667085] hover:text-[#101828] hover:bg-[#F9FAFB]' }}"
                    :title="!sidebarOpen ? 'Settings' : ''"
                >
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('settings') ? 'text-indigo-600' : 'text-[#98A2B3] group-hover:text-[#475467]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Settings</span>
                </a>
            </nav>
    </div>
</aside>
