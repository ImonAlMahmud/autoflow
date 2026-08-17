<aside
    x-cloak
    :class="sidebarOpen ? 'w-64' : 'w-20'"
    class="relative z-30 flex flex-col flex-shrink-0 bg-white border-r border-[#EAECF0] transition-all duration-300 ease-in-out select-none shadow-sidebar min-h-screen"
>
    <!-- Brand Header -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-[#E2E8F0] bg-white">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden group">
            <img src="{{ asset('images/logo.png') }}" alt="Autoflow Logo" class="h-9 w-auto object-contain transition-transform group-hover:scale-105" />
        </a>

        <!-- Collapse Sidebar Toggle (Desktop) -->
        <button
            @click="sidebarOpen = !sidebarOpen"
            type="button"
            class="hidden md:flex items-center justify-center w-8 h-8 rounded-lg text-[#64748B] hover:text-[#0F172A] hover:bg-[#F1F5F9] transition-colors focus:outline-none focus:ring-2 focus:ring-green-500/20"
            :title="sidebarOpen ? 'Collapse sidebar' : 'Expand sidebar'"
        >
            <i class="fa-solid fa-angles-left text-xs transition-transform duration-300" :class="{ 'rotate-180': !sidebarOpen }"></i>
        </button>
    </div>

    <!-- Navigation Scroll Container -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
        
        <!-- SECTION 1: MAIN NAVIGATION -->
        <div>
            <div x-show="sidebarOpen" class="px-3 mb-2 text-[11px] font-semibold tracking-wider text-[#94A3B8] uppercase">
                Overview
            </div>
            <nav class="space-y-1">
                <!-- Dashboard -->
                <a
                    href="{{ route('dashboard') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] shadow-xs' : 'text-[#475569] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
                    :title="!sidebarOpen ? 'Dashboard' : ''"
                >
                    <i class="fa-solid fa-gauge-high w-5 text-center text-sm flex-shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-[#22C55E]' : 'text-[#94A3B8] group-hover:text-[#475569]' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Dashboard</span>
                    @if(request()->routeIs('dashboard'))
                        <span class="absolute right-2.5 w-2 h-2 rounded-full bg-[#22C55E]"></span>
                    @endif
                </a>

                <!-- Websites -->
                <a
                    href="{{ route('websites.index') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('websites.*') ? 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] shadow-xs' : 'text-[#475569] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
                    :title="!sidebarOpen ? 'Websites' : ''"
                >
                    <i class="fa-solid fa-globe w-5 text-center text-sm flex-shrink-0 transition-colors {{ request()->routeIs('websites.*') ? 'text-[#22C55E]' : 'text-[#94A3B8] group-hover:text-[#475569]' }}"></i>
                    <span x-show="sidebarOpen" class="truncate flex-1">Websites</span>
                </a>

                <!-- Content Pages -->
                <a
                    href="{{ route('pages.index') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('pages.*') ? 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] shadow-xs' : 'text-[#475569] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
                    :title="!sidebarOpen ? 'Pages' : ''"
                >
                    <i class="fa-solid fa-file-lines w-5 text-center text-sm flex-shrink-0 transition-colors {{ request()->routeIs('pages.*') ? 'text-[#22C55E]' : 'text-[#94A3B8] group-hover:text-[#475569]' }}"></i>
                    <span x-show="sidebarOpen" class="truncate flex-1">Pages</span>
                </a>
            </nav>
        </div>

        <!-- SECTION 2: AI & AUTOMATION -->
        <div>
            <div x-show="sidebarOpen" class="px-3 mb-2 text-[11px] font-semibold tracking-wider text-[#94A3B8] uppercase">
                AI & Automation
            </div>
            <nav class="space-y-1">
                <!-- AI Models & Prompts -->
                <a
                    href="{{ route('ai.models') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('ai.*') ? 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] shadow-xs' : 'text-[#475569] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
                    :title="!sidebarOpen ? 'AI Models' : ''"
                >
                    <i class="fa-solid fa-brain w-5 text-center text-sm flex-shrink-0 transition-colors {{ request()->routeIs('ai.*') ? 'text-[#22C55E]' : 'text-[#94A3B8] group-hover:text-[#475569]' }}"></i>
                    <span x-show="sidebarOpen" class="truncate flex-1">AI Models</span>
                </a>

                <!-- Automation / Jobs -->
                <a
                    href="{{ route('jobs.index') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('jobs.*') ? 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] shadow-xs' : 'text-[#475569] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
                    :title="!sidebarOpen ? 'Automation Jobs' : ''"
                >
                    <i class="fa-solid fa-gears w-5 text-center text-sm flex-shrink-0 transition-colors {{ request()->routeIs('jobs.*') ? 'text-[#22C55E]' : 'text-[#94A3B8] group-hover:text-[#475569]' }}"></i>
                    <span x-show="sidebarOpen" class="truncate flex-1">Automation Jobs</span>
                </a>
            </nav>
        </div>

        <!-- SECTION 3: SYSTEM & SUPPORT -->
        <div>
            <div x-show="sidebarOpen" class="px-3 mb-2 text-[11px] font-semibold tracking-wider text-[#94A3B8] uppercase">
                Support & Settings
            </div>
            <nav class="space-y-1">
                <!-- How to Use Tutorial (For all users) -->
                <a
                    href="{{ route('how-to-use') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('how-to-use') ? 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] shadow-xs' : 'text-[#475569] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
                    :title="!sidebarOpen ? 'How to Use' : ''"
                >
                    <i class="fa-solid fa-graduation-cap w-5 text-center text-sm flex-shrink-0 transition-colors {{ request()->routeIs('how-to-use') ? 'text-[#22C55E]' : 'text-[#94A3B8] group-hover:text-[#475569]' }}"></i>
                    <span x-show="sidebarOpen" class="truncate flex-1">How to Use (Guide)</span>
                </a>

                <!-- SaaS Subscription & Plans -->
                <a
                    href="{{ route('subscription') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('subscription') ? 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] shadow-xs' : 'text-[#475569] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
                    :title="!sidebarOpen ? 'Subscription & Plans' : ''"
                >
                    <i class="fa-solid fa-gem w-5 text-center text-sm flex-shrink-0 transition-colors {{ request()->routeIs('subscription') ? 'text-[#22C55E]' : 'text-[#94A3B8] group-hover:text-[#475569]' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Subscription & Plans</span>
                </a>

                <!-- Settings -->
                <a
                    href="{{ route('settings') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('settings') ? 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] shadow-xs' : 'text-[#475569] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
                    :title="!sidebarOpen ? 'Settings' : ''"
                >
                    <i class="fa-solid fa-sliders w-5 text-center text-sm flex-shrink-0 transition-colors {{ request()->routeIs('settings') ? 'text-[#22C55E]' : 'text-[#94A3B8] group-hover:text-[#475569]' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Settings</span>
                </a>
            </nav>
        </div>

        <!-- SECTION 4: SUPER ADMIN (GOD MODE) -->
        @if(auth()->user()?->isSuperAdmin())
        <div>
            <div x-show="sidebarOpen" class="px-3 mb-2 text-[11px] font-bold tracking-wider text-amber-600 uppercase flex items-center gap-1.5">
                <i class="fa-solid fa-crown text-[10px] text-amber-500"></i>
                Super Admin Controls
            </div>
            <nav class="space-y-1">
                <a
                    href="{{ route('admin.users') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all duration-150 {{ request()->routeIs('admin.users') ? 'bg-[#0F172A] text-white shadow-xs' : 'text-[#0F172A] bg-amber-50/70 hover:bg-amber-100/70 border border-amber-200/60' }}"
                    :title="!sidebarOpen ? 'Manage Users' : ''"
                >
                    <i class="fa-solid fa-users-gear w-5 text-center text-sm flex-shrink-0 transition-colors {{ request()->routeIs('admin.users') ? 'text-[#22C55E]' : 'text-amber-600' }}"></i>
                    <span x-show="sidebarOpen" class="truncate flex-1">Users & Plans</span>
                    @if(request()->routeIs('admin.users'))
                        <span class="absolute right-2.5 w-2 h-2 rounded-full bg-[#22C55E]"></span>
                    @endif
                </a>

                <!-- System Health (Only for Super Admin) -->
                <a
                    href="{{ route('system-health') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('system-health') ? 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] shadow-xs' : 'text-[#475569] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
                    :title="!sidebarOpen ? 'System Health' : ''"
                >
                    <i class="fa-solid fa-heart-pulse w-5 text-center text-sm flex-shrink-0 transition-colors {{ request()->routeIs('system-health') ? 'text-[#22C55E]' : 'text-[#94A3B8] group-hover:text-[#475569]' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">System Health</span>
                </a>

                <!-- Linux Deployment Guide (Only for Super Admin) -->
                <a
                    href="{{ route('system.deployment') }}"
                    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 {{ request()->routeIs('system.deployment') ? 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] shadow-xs' : 'text-[#475569] hover:text-[#0F172A] hover:bg-[#F8FAFC]' }}"
                    :title="!sidebarOpen ? 'Linux Setup' : ''"
                >
                    <i class="fa-brands fa-linux w-5 text-center text-sm flex-shrink-0 transition-colors {{ request()->routeIs('system.deployment') ? 'text-[#22C55E]' : 'text-[#94A3B8] group-hover:text-[#475569]' }}"></i>
                    <span x-show="sidebarOpen" class="truncate">Linux Server Setup</span>
                </a>
            </nav>
        </div>
        @endif
    </div>
</aside>
