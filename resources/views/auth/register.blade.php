<div class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto space-y-10">
    <div class="text-center space-y-3">
        <span class="px-3.5 py-1.5 rounded-full bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5 shadow-2xs">
            <i class="fa-solid fa-rocket text-xs text-[#22C55E]"></i>
            Start 14-Day Enterprise Free Trial
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">
            Create Your Autoflow Account
        </h1>
        <p class="text-sm text-[#64748B] max-w-lg mx-auto leading-relaxed">
            Automate website refreshes with Cloud & Local AI, preserve HTML/CSS layouts, and sync seamlessly to GitHub.
        </p>
    </div>

        <form wire:submit="register" class="space-y-8 bg-white p-6 sm:p-10 rounded-3xl border border-[#E2E8F0] shadow-xl shadow-green-950/5">
            <!-- Account Details -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-[#0F172A] uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-user text-[#22C55E] text-xs"></i>
                    1. Personal Identity & Credentials
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#334155] mb-1">Full Name *</label>
                        <input
                            wire:model.blur="name"
                            type="text"
                            placeholder="e.g. Imon Mahmud"
                            class="w-full px-4 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-[#0F172A] text-xs focus:bg-white focus:ring-2 focus:ring-[#22C55E] focus:border-transparent transition-all"
                        >
                        @error('name') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#334155] mb-1">Work Email Address *</label>
                        <input
                            wire:model.blur="email"
                            type="email"
                            placeholder="e.g. imon@ideomet.com"
                            class="w-full px-4 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-[#0F172A] text-xs focus:bg-white focus:ring-2 focus:ring-[#22C55E] focus:border-transparent transition-all"
                        >
                        @error('email') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#334155] mb-1">Password *</label>
                        <input
                            wire:model.blur="password"
                            type="password"
                            placeholder="Minimum 6 characters"
                            class="w-full px-4 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-[#0F172A] text-xs focus:bg-white focus:ring-2 focus:ring-[#22C55E] focus:border-transparent transition-all"
                        >
                        @error('password') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#334155] mb-1">Confirm Password *</label>
                        <input
                            wire:model.blur="password_confirmation"
                            type="password"
                            placeholder="Repeat password"
                            class="w-full px-4 py-2.5 rounded-xl bg-[#F8FAFC] border border-[#CBD5E1] text-[#0F172A] text-xs focus:bg-white focus:ring-2 focus:ring-[#22C55E] focus:border-transparent transition-all"
                        >
                    </div>
                </div>
            </div>

            <!-- Free Registration Info Callout -->
            <div class="p-4 rounded-2xl bg-[#F0FDF4] border border-[#DCFCE7] flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-[#22C55E] text-white flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                    <i class="fa-solid fa-gift"></i>
                </div>
                <div class="text-xs space-y-1">
                    <div class="font-bold text-[#15803D]">Free Registration • No Credit Card Required Upfront</div>
                    <p class="text-[#166534] text-[11px] leading-relaxed">
                        Create your account in seconds. You will be able to explore the interface and choose a SaaS Subscription Plan directly from the dashboard whenever you are ready.
                    </p>
                </div>
            </div>

            <!-- Submit Button & Login Link -->
            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-[#64748B]">
                    Already have an account? <a href="{{ route('login') }}" class="text-[#15803D] hover:underline font-bold">Sign In →</a>
                </p>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-extrabold text-xs shadow-xl shadow-green-500/20 transition-all hover:scale-[1.02] flex items-center justify-center gap-2 disabled:opacity-75"
                >
                    <span wire:loading.remove class="flex items-center gap-2">
                        <span>Create Free Account & Continue</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </span>
                    <span wire:loading class="flex items-center gap-2">
                        <i class="fa-solid fa-spinner fa-spin text-xs"></i>
                        <span>Creating Account...</span>
                    </span>
                </button>
            </div>
        </form>
</div>
