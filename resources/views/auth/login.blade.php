<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F7F9FC]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sign In - Autoflow AI Website Refresh System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-[#101828] bg-[#F7F9FC] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
        
        <!-- Brand Header -->
        <div class="text-center">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg mx-auto mb-4">
                <svg class="w-6 h-6 shrink-0 block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-[#101828] tracking-tight">Autoflow Admin</h2>
            <p class="mt-1 text-xs text-[#667085]">Autonomous AI Website Content Refresher & Git Sync</p>
        </div>

        <!-- Card Form Container -->
        <div class="bg-white py-8 px-6 sm:px-8 shadow-card rounded-2xl border border-[#EAECF0]">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Session Status / Errors -->
                @if ($errors->any())
                    <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-[#344054] mb-1.5">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D0D5DD] text-sm text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all shadow-xs"
                        placeholder="admin@autoflow.io"
                    >
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-[#344054]">Password</label>
                        <a href="#" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">Forgot password?</a>
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-[#D0D5DD] text-sm text-[#101828] placeholder-[#98A2B3] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all shadow-xs"
                        placeholder="••••••••"
                    >
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-xs text-[#475467] cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-[#D0D5DD] text-indigo-600 focus:ring-indigo-500">
                        <span>Remember me for 30 days</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                    >
                        Sign In to Dashboard
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-xs text-[#98A2B3]">
            Autoflow v1.0 • White SaaS Dashboard Theme
        </p>
    </div>
</body>
</html>
