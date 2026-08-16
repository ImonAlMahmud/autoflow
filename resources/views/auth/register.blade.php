<x-marketing-layout>
    <div class="py-16 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto space-y-12">
        <div class="text-center space-y-3">
            <span class="px-3.5 py-1.5 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-xs font-bold uppercase tracking-wider">
                Start 14-Day Enterprise Trial
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                Create Your Autoflow Account
            </h1>
            <p class="text-sm text-slate-400 max-w-lg mx-auto">
                Automate website refreshes, preserve CSS gradient styles, and sync seamlessly to GitHub.
            </p>
        </div>

        <form wire:submit.prevent="register" class="space-y-8 bg-slate-900/80 p-8 rounded-3xl border border-slate-800 backdrop-blur-xl shadow-2xl">
            <!-- Account Details -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">1. Personal Identity & Credentials</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Full Name *</label>
                        <input
                            wire:model="name"
                            type="text"
                            placeholder="e.g. Imon Mahmud"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                        @error('name') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Work Email Address *</label>
                        <input
                            wire:model="email"
                            type="email"
                            placeholder="e.g. imon@ideomet.com"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                        @error('email') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Password *</label>
                        <input
                            wire:model="password"
                            type="password"
                            placeholder="Minimum 6 characters"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                        @error('password') <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Confirm Password *</label>
                        <input
                            wire:model="password_confirmation"
                            type="password"
                            placeholder="Repeat password"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                    </div>
                </div>
            </div>

            <!-- 3 Plan Cards -->
            <div class="space-y-4 pt-4 border-t border-slate-800">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">2. Select Your SaaS Plan</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Starter Card -->
                    <label class="p-5 rounded-2xl border-2 cursor-pointer transition-all flex flex-col justify-between space-y-4 {{ $plan === 'starter' ? 'border-indigo-500 bg-indigo-950/40 shadow-lg shadow-indigo-500/10' : 'border-slate-800 bg-slate-950/60 hover:border-slate-700' }}">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Starter</span>
                                <input type="radio" wire:model.live="plan" value="starter" class="text-indigo-600">
                            </div>
                            <p class="text-2xl font-extrabold text-white">$29 <span class="text-xs font-normal text-slate-400">/mo</span></p>
                            <ul class="text-[11px] text-slate-300 space-y-1.5 pt-2">
                                <li>✓ 3 Active Websites</li>
                                <li>✓ 100 AI Rewrites/mo</li>
                                <li>✓ Git Sync & Auto-push</li>
                            </ul>
                        </div>
                    </label>

                    <!-- Pro Card (Featured) -->
                    <label class="p-5 rounded-2xl border-2 cursor-pointer transition-all flex flex-col justify-between space-y-4 relative {{ $plan === 'pro' ? 'border-indigo-500 bg-indigo-950/40 shadow-lg shadow-indigo-500/20' : 'border-slate-800 bg-slate-950/60 hover:border-slate-700' }}">
                        <span class="absolute -top-3 right-4 px-2.5 py-0.5 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-extrabold text-[9px] uppercase tracking-wider">POPULAR</span>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-indigo-300 uppercase tracking-wider">Pro Agency</span>
                                <input type="radio" wire:model.live="plan" value="pro" class="text-indigo-600">
                            </div>
                            <p class="text-2xl font-extrabold text-white">$79 <span class="text-xs font-normal text-slate-400">/mo</span></p>
                            <ul class="text-[11px] text-slate-300 space-y-1.5 pt-2">
                                <li>✓ 25 Active Websites</li>
                                <li>✓ Unlimited Rewrites</li>
                                <li>✓ 50+ Site Log Filter</li>
                                <li>✓ Priority Groq Llama 3.3</li>
                            </ul>
                        </div>
                    </label>

                    <!-- Enterprise Card -->
                    <label class="p-5 rounded-2xl border-2 cursor-pointer transition-all flex flex-col justify-between space-y-4 {{ $plan === 'enterprise' ? 'border-purple-500 bg-purple-950/40 shadow-lg shadow-purple-500/10' : 'border-slate-800 bg-slate-950/60 hover:border-slate-700' }}">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-purple-300 uppercase tracking-wider">Enterprise</span>
                                <input type="radio" wire:model.live="plan" value="enterprise" class="text-purple-600">
                            </div>
                            <p class="text-2xl font-extrabold text-white">$199 <span class="text-xs font-normal text-slate-400">/mo</span></p>
                            <ul class="text-[11px] text-slate-300 space-y-1.5 pt-2">
                                <li>✓ Unlimited Websites</li>
                                <li>✓ 1-Minute Cron Frequency</li>
                                <li>✓ Custom AI Prompts</li>
                                <li>✓ Dedicated SLA & Manager</li>
                            </ul>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Button & Login Link -->
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-400">
                    Already have an account? <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold underline">Sign In →</a>
                </p>

                <button
                    type="submit"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-extrabold text-xs shadow-xl shadow-indigo-600/30 transition-all hover:scale-105"
                >
                    Create Account & Access Dashboard →
                </button>
            </div>
        </form>
    </div>
</x-marketing-layout>
