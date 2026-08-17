<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <!-- Font Awesome 6 Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Autoflow — AI Website Refresh & Git Automation Platform by Ideomet Technologies' }}</title>
    <meta name="description" content="Autoflow by Ideomet Technologies: The enterprise AI platform for automated website content refreshes, SEO optimization, and instant GitHub sync.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
                            50: '#F0FDF4',
                            100: '#DCFCE7',
                            500: '#22C55E',
                            600: '#16A34A',
                            700: '#15803D',
                            900: '#0F172A',
                        }
                    }
                }
            }
        }
    </script>
    @livewireStyles
    <style>
        .gradient-text { background: linear-gradient(135deg, #15803D 0%, #22C55E 50%, #16A34A 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
        .card-hover { transition: transform .25s ease, box-shadow .25s ease; }
        @media (hover: hover) { .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 60px rgba(34,197,94,.15); } }
        .nav-active::after { content:''; position:absolute; bottom:-4px; left:0; right:0; height:2px; background:#22C55E; border-radius:2px; }
        .nav-active { position:relative; }
        @keyframes floatY { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .animate-float { animation: floatY 5s ease-in-out infinite; }
        @media (max-width: 640px) { .animate-float { animation: none; } }
        @keyframes pulseDot { 0%,100%{box-shadow:0 0 0 0 rgba(34,197,94,.5)} 50%{box-shadow:0 0 0 6px rgba(34,197,94,0)} }
        .pulse-dot { animation: pulseDot 2s ease-in-out infinite; }
        [data-reveal] { opacity:0; transform:translateY(28px); transition:opacity .65s ease,transform .65s ease; }
        [data-reveal].revealed { opacity:1; transform:translateY(0); }
        [data-reveal-stagger] > * { opacity:0; transform:translateY(24px); transition:opacity .55s ease,transform .55s ease; }
        [data-reveal-stagger].revealed > *:nth-child(1){opacity:1;transform:translateY(0);transition-delay:0ms}
        [data-reveal-stagger].revealed > *:nth-child(2){opacity:1;transform:translateY(0);transition-delay:90ms}
        [data-reveal-stagger].revealed > *:nth-child(3){opacity:1;transform:translateY(0);transition-delay:180ms}
        [data-reveal-stagger].revealed > *:nth-child(4){opacity:1;transform:translateY(0);transition-delay:270ms}
        [data-reveal-stagger].revealed > *:nth-child(5){opacity:1;transform:translateY(0);transition-delay:360ms}
        [data-reveal-stagger].revealed > *:nth-child(6){opacity:1;transform:translateY(0);transition-delay:450ms}
        @media (max-width: 640px) {
            [data-reveal-stagger].revealed > * { opacity:1; transform:translateY(0); transition-delay:0ms !important; }
        }
        .mono-truncate { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(el => {
                    if (el.isIntersecting) { el.target.classList.add('revealed'); }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
            document.querySelectorAll('[data-reveal], [data-reveal-stagger]').forEach(el => observer.observe(el));
        });
    </script>
</head>
<body class="font-sans antialiased bg-white text-[#0F172A] flex flex-col min-h-screen" x-data="{ mobileMenuOpen: false }">

    <!-- ======================== MODERN SEAMLESS HEADER NAV ======================== -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-[#E2E8F0]/80 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18 sm:h-20">

                <!-- Logo & Brand Badge -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ asset('images/logo.png') }}" alt="Autoflow Logo" class="h-9 sm:h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105" />
                    </a>
                    <span class="hidden lg:inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] tracking-wider uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E] animate-pulse"></span>
                        AI 2.0
                    </span>
                </div>

                <!-- Center Floating Navigation Links -->
                <nav class="hidden md:flex items-center gap-1 bg-[#F8FAFC] border border-[#E2E8F0] p-1.5 rounded-2xl shadow-inner-xs">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 {{ request()->routeIs('home') ? 'bg-white text-[#15803D] shadow-xs border border-[#E2E8F0]' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-white/60' }}">
                        <i class="fa-solid fa-house text-[11px] {{ request()->routeIs('home') ? 'text-[#22C55E]' : 'opacity-60' }}"></i>
                        Home
                    </a>
                    <a href="{{ route('about') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 {{ request()->routeIs('about') ? 'bg-white text-[#15803D] shadow-xs border border-[#E2E8F0]' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-white/60' }}">
                        <i class="fa-solid fa-building text-[11px] {{ request()->routeIs('about') ? 'text-[#22C55E]' : 'opacity-60' }}"></i>
                        About
                    </a>
                    <a href="{{ route('how-it-works') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 {{ request()->routeIs('how-it-works') ? 'bg-white text-[#15803D] shadow-xs border border-[#E2E8F0]' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-white/60' }}">
                        <i class="fa-solid fa-gears text-[11px] {{ request()->routeIs('how-it-works') ? 'text-[#22C55E]' : 'opacity-60' }}"></i>
                        How It Works
                    </a>
                    <a href="{{ route('pricing') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 {{ request()->routeIs('pricing') ? 'bg-white text-[#15803D] shadow-xs border border-[#E2E8F0]' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-white/60' }}">
                        <i class="fa-solid fa-tag text-[11px] {{ request()->routeIs('pricing') ? 'text-[#22C55E]' : 'opacity-60' }}"></i>
                        Pricing
                    </a>
                    <a href="{{ route('contact') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 {{ request()->routeIs('contact') ? 'bg-white text-[#15803D] shadow-xs border border-[#E2E8F0]' : 'text-[#64748B] hover:text-[#0F172A] hover:bg-white/60' }}">
                        <i class="fa-solid fa-envelope text-[11px] {{ request()->routeIs('contact') ? 'text-[#22C55E]' : 'opacity-60' }}"></i>
                        Contact
                    </a>
                </nav>

                <!-- Right Action Buttons -->
                <div class="hidden md:flex items-center gap-2.5">
                    @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-[#0F172A] hover:bg-slate-800 text-white font-bold text-xs transition-all shadow-xs flex items-center gap-2 hover:scale-[1.02]">
                        <i class="fa-solid fa-gauge-high text-[#22C55E]"></i>Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="px-4 py-2.5 text-[#0F172A] hover:text-[#15803D] font-bold text-xs transition-colors rounded-xl hover:bg-[#F8FAFC]">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs shadow-md shadow-green-500/20 transition-all hover:scale-[1.03] flex items-center gap-2">
                        <i class="fa-solid fa-rocket text-xs"></i>
                        <span>Start Free Trial</span>
                    </a>
                    @endauth
                </div>

                <!-- Mobile Toggle Hamburger -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden w-10 h-10 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] flex items-center justify-center transition-colors hover:bg-white">
                    <i class="fa-solid text-base" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars-staggered'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu with Smooth Animation -->
        <div
            x-show="mobileMenuOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden bg-white/95 backdrop-blur-xl border-t border-[#E2E8F0] px-4 py-4 shadow-xl space-y-2"
        >
                <div class="space-y-1">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition-colors {{ request()->routeIs('home') ? 'bg-[#F0FDF4] text-[#15803D]' : 'text-[#64748B] hover:bg-[#F8FAFC]' }}">
                        <i class="fa-solid fa-house w-4 text-center text-[#22C55E]"></i>Home
                    </a>
                    <a href="{{ route('about') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition-colors {{ request()->routeIs('about') ? 'bg-[#F0FDF4] text-[#15803D]' : 'text-[#64748B] hover:bg-[#F8FAFC]' }}">
                        <i class="fa-solid fa-building w-4 text-center text-[#22C55E]"></i>About Ideomet
                    </a>
                    <a href="{{ route('how-it-works') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition-colors {{ request()->routeIs('how-it-works') ? 'bg-[#F0FDF4] text-[#15803D]' : 'text-[#64748B] hover:bg-[#F8FAFC]' }}">
                        <i class="fa-solid fa-gears w-4 text-center text-[#22C55E]"></i>How It Works
                    </a>
                    <a href="{{ route('pricing') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition-colors {{ request()->routeIs('pricing') ? 'bg-[#F0FDF4] text-[#15803D]' : 'text-[#64748B] hover:bg-[#F8FAFC]' }}">
                        <i class="fa-solid fa-tag w-4 text-center text-[#22C55E]"></i>Pricing Plans
                    </a>
                    <a href="{{ route('contact') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition-colors {{ request()->routeIs('contact') ? 'bg-[#F0FDF4] text-[#15803D]' : 'text-[#64748B] hover:bg-[#F8FAFC]' }}">
                        <i class="fa-solid fa-envelope w-4 text-center text-[#22C55E]"></i>Contact Team
                    </a>
                </div>

                <div class="pt-3 border-t border-[#E2E8F0] space-y-2">
                    @auth
                    <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-[#0F172A] text-white font-bold text-xs shadow-xs">
                        <i class="fa-solid fa-gauge-high text-[#22C55E]"></i>Go to Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center py-2.5 rounded-xl border border-[#CBD5E1] text-[#0F172A] font-bold text-xs">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs shadow-md shadow-green-500/20">
                        <i class="fa-solid fa-rocket"></i>Get Started Free
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#0F172A] text-gray-400 pt-16 pb-10 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Autoflow Logo" class="h-8 w-auto object-contain brightness-0 invert" />
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">Enterprise AI automation for perpetual website content refresh and automatic GitHub deployment.</p>
                    <p class="text-[#22C55E] text-sm font-semibold"><i class="fa-solid fa-building-columns mr-1.5"></i>A Product by Ideomet Technologies</p>
                    <div class="flex items-center gap-2 text-xs text-[#22C55E]">
                        <span class="w-2 h-2 rounded-full bg-[#22C55E] pulse-dot inline-block"></span>AI Engine Operational
                    </div>
                </div>
                <div class="space-y-3">
                    <p class="text-white font-bold text-xs uppercase tracking-widest">Product</p>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[#22C55E] text-xs"></i>Platform Overview</a></li>
                        <li><a href="{{ route('how-it-works') }}" class="hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[#22C55E] text-xs"></i>How It Works</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[#22C55E] text-xs"></i>Pricing Plans</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[#22C55E] text-xs"></i>App Dashboard</a></li>
                    </ul>
                </div>
                <div class="space-y-3">
                    <p class="text-white font-bold text-xs uppercase tracking-widest">Company</p>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[#22C55E] text-xs"></i>About Ideomet</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[#22C55E] text-xs"></i>Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[#22C55E] text-xs"></i>Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[#22C55E] text-xs"></i>Terms of Service</a></li>
                    </ul>
                </div>
                <div class="space-y-3">
                    <p class="text-white font-bold text-xs uppercase tracking-widest">Technology</p>
                    <div class="space-y-2.5 text-sm">
                        <div class="flex items-center gap-2.5"><i class="fa-brands fa-github text-gray-400 w-4 text-center"></i><span>GitHub Integration</span></div>
                        <div class="flex items-center gap-2.5"><i class="fa-solid fa-brain text-[#22C55E] w-4 text-center"></i><span>Groq Llama 3.3 70B</span></div>
                        <div class="flex items-center gap-2.5"><i class="fa-brands fa-laravel text-red-400 w-4 text-center"></i><span>Laravel 11 + Livewire 3</span></div>
                        <div class="flex items-center gap-2.5"><i class="fa-solid fa-shield-halved text-emerald-400 w-4 text-center"></i><span>CSS-Safe Rewriting</span></div>
                        <div class="flex items-center gap-2.5"><i class="fa-solid fa-clock text-amber-400 w-4 text-center"></i><span>CPanel Cron Scheduler</span></div>
                    </div>
                </div>
            </div>
            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-gray-500">
                <p><i class="fa-regular fa-copyright mr-1"></i>{{ date('Y') }} Ideomet Technologies. All rights reserved.</p>
                <p>Autoflow is a registered product of Ideomet Technologies Limited.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
