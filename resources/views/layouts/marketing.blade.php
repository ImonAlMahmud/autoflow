<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Autoflow — AI Website Refresh & Git Automation Platform by Ideomet Technologies' }}</title>
    <meta name="description" content="Autoflow by Ideomet Technologies: The enterprise AI platform for automated website content refreshes, SEO optimization, and instant GitHub sync.">

    <!-- Font Awesome 6 Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full font-sans antialiased text-slate-900 bg-slate-950 selection:bg-emerald-500 selection:text-white flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false }">

    <!-- Background Ambient Glow Effects -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-[#22C55E]/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 -right-40 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl"></div>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 backdrop-blur-xl bg-slate-950/80 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Brand Identifier -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 group-hover:scale-105 transition-all">
                        <i class="fa-solid fa-bolt text-xs"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-xl text-white tracking-tight leading-none flex items-center gap-2">
                            Autoflow
                            <span class="text-[10px] font-semibold text-indigo-300 bg-indigo-950/80 px-2 py-0.5 rounded-full border border-indigo-700/50">PRO</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium tracking-wide mt-0.5">By Ideomet Technologies</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold">
                    <a href="{{ route('home') }}" class="transition-colors {{ request()->routeIs('home') ? 'text-indigo-400 font-bold' : 'text-slate-300 hover:text-white' }}">
                        Home
                    </a>
                    <a href="{{ route('about') }}" class="transition-colors {{ request()->routeIs('about') ? 'text-indigo-400 font-bold' : 'text-slate-300 hover:text-white' }}">
                        About
                    </a>
                    <a href="{{ route('how-it-works') }}" class="transition-colors {{ request()->routeIs('how-it-works') ? 'text-indigo-400 font-bold' : 'text-slate-300 hover:text-white' }}">
                        How It Works
                    </a>
                    <a href="{{ route('pricing') }}" class="transition-colors {{ request()->routeIs('pricing') ? 'text-indigo-400 font-bold' : 'text-slate-300 hover:text-white' }}">
                        Pricing
                    </a>
                    <a href="{{ route('contact') }}" class="transition-colors {{ request()->routeIs('contact') ? 'text-indigo-400 font-bold' : 'text-slate-300 hover:text-white' }}">
                        Contact
                    </a>
                </nav>

                <!-- CTA Action Button -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition-all hover:scale-105 flex items-center gap-2">
                        <span>Launch App Dashboard</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="md:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-900 transition-colors">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-slate-950 border-b border-slate-800 px-4 py-6 space-y-4 text-sm font-semibold">
            <a href="{{ route('home') }}" class="block text-slate-300 hover:text-white py-1">Home</a>
            <a href="{{ route('about') }}" class="block text-slate-300 hover:text-white py-1">About</a>
            <a href="{{ route('how-it-works') }}" class="block text-slate-300 hover:text-white py-1">How It Works</a>
            <a href="{{ route('pricing') }}" class="block text-slate-300 hover:text-white py-1">Pricing</a>
            <a href="{{ route('contact') }}" class="block text-slate-300 hover:text-white py-1">Contact</a>
            <div class="pt-4 border-t border-slate-800">
                <a href="{{ route('dashboard') }}" class="w-full justify-center px-5 py-3 rounded-xl bg-[#22C55E] text-white font-bold text-xs flex items-center gap-2">
                    Launch App Dashboard →
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 relative z-10">
        {{ $slot }}
    </main>

    <!-- Global Footer -->
    <footer class="relative z-10 bg-slate-950 border-t border-slate-800/80 pt-16 pb-12 text-slate-400 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Brand Info -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#22C55E] flex items-center justify-center text-white font-bold">
                            ⚡
                        </div>
                        <span class="font-extrabold text-lg text-white">Autoflow</span>
                    </div>
                    <p class="text-slate-400 leading-relaxed">
                        Enterprise AI automation platform for continuous website content refresh, layout preservation, and automatic GitHub deployment.
                    </p>
                    <p class="text-indigo-400 font-semibold">
                        A Product by Ideomet Technologies
                    </p>
                </div>

                <!-- Col 2: Navigation -->
                <div class="space-y-3">
                    <p class="font-bold text-white uppercase tracking-wider text-[11px]">Product</p>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Platform Overview</a></li>
                        <li><a href="{{ route('how-it-works') }}" class="hover:text-white transition-colors">How It Works</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-white transition-colors">Pricing Plans</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">App Dashboard</a></li>
                    </ul>
                </div>

                <!-- Col 3: Company -->
                <div class="space-y-3">
                    <p class="font-bold text-white uppercase tracking-wider text-[11px]">Ideomet Technologies</p>
                    <ul class="space-y-2">
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About Ideomet</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Enterprise Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>

                <!-- Col 4: Tech Badge -->
                <div class="space-y-3">
                    <p class="font-bold text-white uppercase tracking-wider text-[11px]">Engineering</p>
                    <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-2">
                        <div class="flex items-center gap-2 text-emerald-400 font-semibold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            AI Engine Operational
                        </div>
                        <p class="text-slate-400 text-[11px]">Powered by Groq LLM & Native Git Remote Automation.</p>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="pt-8 border-t border-slate-800/60 flex flex-col sm:flex-row items-center justify-between gap-4 text-slate-500 text-[11px]">
                <p>© {{ date('Y') }} Ideomet Technologies. All rights reserved.</p>
                <p>Autoflow™ is a registered product of Ideomet Technologies Limited.</p>
            </div>
        </div>
    </footer>

</body>
</html>
