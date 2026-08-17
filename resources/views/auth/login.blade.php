<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F8FAFC]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sign In - Autoflow AI Website Refresh Platform</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
</head>
<body class="h-full font-sans antialiased text-[#0F172A] bg-[#F8FAFC] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
        
        <!-- Brand Header -->
        <div class="text-center space-y-2">
            <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105">
                <img src="{{ asset('images/logo.png') }}" alt="Autoflow Logo" class="h-10 w-auto mx-auto object-contain" />
            </a>
            <h2 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Welcome Back</h2>
            <p class="text-xs text-[#64748B]">Sign in to manage autonomous AI website refreshes & Git deployments</p>
        </div>

        <!-- Card Form Container -->
        <div class="bg-white py-8 px-6 sm:px-8 shadow-xl shadow-green-950/5 rounded-3xl border border-[#E2E8F0]">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Session Status / Errors -->
                @if ($errors->any())
                    <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation text-rose-500"></i>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-[#334155] mb-1.5">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-2.5 rounded-xl border border-[#CBD5E1] bg-[#F8FAFC] text-xs text-[#0F172A] placeholder-[#94A3B8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#22C55E] focus:border-transparent transition-all shadow-xs"
                        placeholder="admin@autoflow.local"
                    >
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-[#334155]">Password</label>
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-[#CBD5E1] bg-[#F8FAFC] text-xs text-[#0F172A] placeholder-[#94A3B8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#22C55E] focus:border-transparent transition-all shadow-xs"
                        placeholder="••••••••"
                    >
                </div>

                <!-- Remember Me & Register link -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-[#64748B] cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-[#CBD5E1] text-[#22C55E] focus:ring-[#22C55E]">
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('register') }}" class="text-[#15803D] hover:underline font-bold">Create Account →</a>
                </div>

                <!-- Submit Button -->
                <div>
                    <button
                        type="submit"
                        class="w-full py-3 px-4 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-extrabold text-xs transition-all shadow-lg shadow-green-500/20 hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-[#22C55E]/30 flex items-center justify-center gap-2"
                    >
                        <span>Sign In to Dashboard</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center space-y-2">
            <p class="text-xs text-[#64748B]">
                <a href="{{ route('home') }}" class="text-[#64748B] hover:text-[#0F172A] font-semibold transition-colors">
                    ← Back to Autoflow Home
                </a>
            </p>
            <p class="text-[11px] text-[#94A3B8]">
                A Product by Ideomet Technologies
            </p>
        </div>
    </div>
</body>
</html>
