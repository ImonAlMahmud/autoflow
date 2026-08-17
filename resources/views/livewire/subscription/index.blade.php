<div class="space-y-8">
    <!-- Header Card -->
    <div class="bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-800 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-semibold uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-gem text-xs text-[#22C55E]"></i>
                    SaaS Subscription Portal
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-crown text-amber-400"></i>
                Your Subscription & Usage Quota
            </h1>
            <p class="text-xs text-slate-300 max-w-2xl leading-relaxed">
                Manage your active plan, connected website limits, and upgrade your SaaS subscription.
            </p>
        </div>

        <!-- Current Active Badge -->
        <div class="px-5 py-3 rounded-2xl bg-slate-950/80 border border-emerald-500/30 flex flex-col items-end">
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Active Plan</span>
            <span class="text-sm font-extrabold text-[#22C55E]">{{ $user->plan_badge ?? 'STARTER PLAN' }}</span>
        </div>
    </div>

    <!-- Usage Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Card 1: Connected Websites -->
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-card p-6 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#667085] uppercase tracking-wider">Connected Websites</span>
                <span class="text-xs font-bold text-[#15803D] bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                    {{ $websiteCount }} / {{ $user->websites_limit ?? 3 }} Used
                </span>
            </div>
            <p class="text-2xl font-extrabold text-[#0F172A]">{{ $websiteCount }} <span class="text-xs text-[#667085] font-normal">Active Sites</span></p>
            
            <div class="w-full bg-[#EAECF0] h-2 rounded-full overflow-hidden">
                <div class="bg-[#22C55E] h-full rounded-full transition-all" style="width: {{ min(100, ($websiteCount / max(1, $user->websites_limit ?? 3)) * 100) }}%"></div>
            </div>
        </div>

        <!-- Card 2: AI Rewrites This Month -->
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-card p-6 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#667085] uppercase tracking-wider">Monthly Rewrites</span>
                <span class="text-xs font-bold text-[#15803D] bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                    Quota Active
                </span>
            </div>
            <p class="text-2xl font-extrabold text-[#0F172A]">{{ $user->rewrites_used_this_month ?? 0 }} <span class="text-xs text-[#667085] font-normal">/ {{ $user->monthly_rewrites_limit > 90000 ? 'Unlimited' : ($user->monthly_rewrites_limit ?? 100) }}</span></p>
            
            <div class="w-full bg-[#EAECF0] h-2 rounded-full overflow-hidden">
                <div class="bg-[#16A34A] h-full rounded-full transition-all" style="width: {{ min(100, (($user->rewrites_used_this_month ?? 0) / max(1, $user->monthly_rewrites_limit ?? 100)) * 100) }}%"></div>
            </div>
        </div>

        <!-- Card 3: Status -->
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-card p-6 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#667085] uppercase tracking-wider">Billing Status</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600 uppercase tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                {{ ucfirst($user->plan_status ?? 'Active') }}
            </p>
            <p class="text-xs text-[#667085]">Auto-renewing account managed by Ideomet Technologies.</p>
        </div>
    </div>

    <!-- Upgrade / Switch Plans -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-card p-6 space-y-6">
        <div class="border-b border-[#EAECF0] pb-3">
            <h3 class="text-sm font-bold text-[#0F172A]">Available SaaS Subscriptions</h3>
            <p class="text-xs text-[#667085]">Switch your subscription tier in 1-click to unlock additional websites and features</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Starter -->
            <div class="rounded-2xl border p-6 space-y-5 flex flex-col justify-between {{ $user->plan === 'starter' ? 'border-[#22C55E] bg-[#F0FDF4]' : 'border-[#EAECF0] bg-white hover:border-gray-300' }}">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Starter</span>
                        @if($user->plan === 'starter')
                            <span class="px-2.5 py-0.5 rounded-full bg-[#0F172A] text-white font-extrabold text-[10px]">CURRENT</span>
                        @endif
                    </div>
                    <p class="text-3xl font-extrabold text-[#0F172A]">$29 <span class="text-xs text-[#667085] font-normal">/mo</span></p>
                    <ul class="text-xs text-[#475467] space-y-2 pt-2">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#22C55E] text-xs"></i> 3 Active Websites</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#22C55E] text-xs"></i> 100 AI Rewrites/mo</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#22C55E] text-xs"></i> Git Sync & Auto-push</li>
                    </ul>
                </div>

                @if($user->plan !== 'starter')
                    <button
                        wire:click="switchPlan('starter')"
                        type="button"
                        class="w-full py-2.5 rounded-xl border border-[#D0D5DD] bg-white hover:bg-[#F9FAFB] text-xs font-bold text-[#344054] transition-all flex items-center justify-center gap-1.5"
                    >
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                        Switch to Starter
                    </button>
                @endif
            </div>

            <!-- Pro Agency -->
            <div class="rounded-2xl border-2 p-6 space-y-5 flex flex-col justify-between relative {{ $user->plan === 'pro' ? 'border-[#22C55E] bg-[#F0FDF4] shadow-md' : 'border-[#DCFCE7] bg-white' }}">
                <span class="absolute -top-3 right-4 px-2.5 py-0.5 rounded-full bg-gradient-to-r from-[#16A34A] to-[#22C55E] text-white font-extrabold text-[9px] uppercase tracking-wider shadow-sm">
                    <i class="fa-solid fa-crown mr-1"></i>POPULAR
                </span>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#15803D] uppercase tracking-wider">Pro Agency</span>
                        @if($user->plan === 'pro')
                            <span class="px-2.5 py-0.5 rounded-full bg-[#22C55E] text-white font-extrabold text-[10px]">CURRENT</span>
                        @endif
                    </div>
                    <p class="text-3xl font-extrabold text-[#0F172A]">$79 <span class="text-xs text-[#64748B] font-normal">/mo</span></p>
                    <ul class="text-xs text-[#334155] space-y-2 pt-2">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#22C55E] text-xs"></i> 25 Active Websites</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#22C55E] text-xs"></i> Unlimited Rewrites</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#22C55E] text-xs"></i> 50+ Site Log Filter</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#22C55E] text-xs"></i> Priority Groq Llama 3.3</li>
                    </ul>
                </div>

                @if($user->plan !== 'pro')
                    <button
                        wire:click="switchPlan('pro')"
                        type="button"
                        class="w-full py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs shadow-sm transition-all hover:scale-105 flex items-center justify-center gap-1.5"
                    >
                        <i class="fa-solid fa-rocket text-xs"></i>
                        Upgrade to Pro ($79/mo)
                    </button>
                @endif
            </div>

            <!-- Enterprise -->
            <div class="rounded-2xl border p-6 space-y-5 flex flex-col justify-between {{ $user->plan === 'enterprise' ? 'border-[#0F172A] bg-slate-50 shadow-md' : 'border-[#EAECF0] bg-white hover:border-gray-300' }}">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#0F172A] uppercase tracking-wider">Enterprise</span>
                        @if($user->plan === 'enterprise')
                            <span class="px-2.5 py-0.5 rounded-full bg-[#0F172A] text-white font-extrabold text-[10px]">CURRENT</span>
                        @endif
                    </div>
                    <p class="text-3xl font-extrabold text-[#0F172A]">$199 <span class="text-xs text-[#667085] font-normal">/mo</span></p>
                    <ul class="text-xs text-[#475467] space-y-2 pt-2">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#22C55E] text-xs"></i> Unlimited Websites</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#22C55E] text-xs"></i> 1-Minute Cron Frequency</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#22C55E] text-xs"></i> Custom AI Prompts</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-[#22C55E] text-xs"></i> Dedicated SLA & Manager</li>
                    </ul>
                </div>

                @if($user->plan !== 'enterprise')
                    <button
                        wire:click="switchPlan('enterprise')"
                        type="button"
                        class="w-full py-2.5 rounded-xl bg-[#0F172A] hover:bg-slate-800 text-white font-bold text-xs shadow-sm transition-all hover:scale-105 flex items-center justify-center gap-1.5"
                    >
                        <i class="fa-solid fa-crown text-amber-400 text-xs"></i>
                        Upgrade to Enterprise
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
