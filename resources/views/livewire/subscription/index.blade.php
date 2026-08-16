<div class="space-y-8">
    <!-- Header Card -->
    <div class="bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-800 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-xs font-semibold uppercase tracking-wider">
                    SaaS Subscription Portal
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                💎 Your Subscription & Usage Quota
            </h1>
            <p class="text-xs text-slate-300 max-w-2xl leading-relaxed">
                Manage your active plan, connected website limits, and upgrade your SaaS subscription.
            </p>
        </div>

        <!-- Current Active Badge -->
        <div class="px-5 py-3 rounded-2xl bg-indigo-950/80 border border-indigo-600/50 flex flex-col items-end">
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Active Plan</span>
            <span class="text-sm font-extrabold text-indigo-300">{{ $user->plan_badge ?? 'STARTER PLAN' }}</span>
        </div>
    </div>

    <!-- Usage Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Card 1: Connected Websites -->
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#667085] uppercase tracking-wider">Connected Websites</span>
                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">
                    {{ $websiteCount }} / {{ $user->websites_limit ?? 3 }} Used
                </span>
            </div>
            <p class="text-2xl font-extrabold text-[#101828]">{{ $websiteCount }} <span class="text-xs text-[#667085] font-normal">Active Sites</span></p>
            
            <div class="w-full bg-[#EAECF0] h-2 rounded-full overflow-hidden">
                <div class="bg-indigo-600 h-full rounded-full transition-all" style="width: {{ min(100, ($websiteCount / max(1, $user->websites_limit ?? 3)) * 100) }}%"></div>
            </div>
        </div>

        <!-- Card 2: AI Rewrites This Month -->
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#667085] uppercase tracking-wider">Monthly Rewrites</span>
                <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full border border-purple-100">
                    Quota Active
                </span>
            </div>
            <p class="text-2xl font-extrabold text-[#101828]">{{ $user->rewrites_used_this_month ?? 0 }} <span class="text-xs text-[#667085] font-normal">/ {{ $user->monthly_rewrites_limit > 90000 ? 'Unlimited' : ($user->monthly_rewrites_limit ?? 100) }}</span></p>
            
            <div class="w-full bg-[#EAECF0] h-2 rounded-full overflow-hidden">
                <div class="bg-purple-600 h-full rounded-full transition-all" style="width: {{ min(100, (($user->rewrites_used_this_month ?? 0) / max(1, $user->monthly_rewrites_limit ?? 100)) * 100) }}%"></div>
            </div>
        </div>

        <!-- Card 3: Status -->
        <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-[#667085] uppercase tracking-wider">Billing Status</span>
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600 uppercase tracking-tight">Active</p>
            <p class="text-xs text-[#667085]">Auto-renewing account managed by Ideomet Technologies.</p>
        </div>
    </div>

    <!-- Upgrade / Switch Plans -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-6">
        <div class="border-b border-[#EAECF0] pb-3">
            <h3 class="text-sm font-bold text-[#101828]">Available SaaS Subscriptions</h3>
            <p class="text-xs text-[#667085]">Switch your subscription tier in 1-click to unlock additional websites and features</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Starter -->
            <div class="rounded-2xl border p-6 space-y-5 flex flex-col justify-between {{ $user->plan === 'starter' ? 'border-indigo-600 bg-indigo-50/30' : 'border-[#EAECF0] bg-white' }}">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#101828] uppercase tracking-wider">Starter</span>
                        @if($user->plan === 'starter')
                            <span class="px-2.5 py-0.5 rounded-full bg-indigo-600 text-white font-extrabold text-[10px]">CURRENT</span>
                        @endif
                    </div>
                    <p class="text-3xl font-extrabold text-[#101828]">$29 <span class="text-xs text-[#667085] font-normal">/mo</span></p>
                    <ul class="text-xs text-[#475467] space-y-2 pt-2">
                        <li>✓ 3 Active Websites</li>
                        <li>✓ 100 AI Rewrites/mo</li>
                        <li>✓ Git Sync & Auto-push</li>
                    </ul>
                </div>

                @if($user->plan !== 'starter')
                    <button
                        wire:click="switchPlan('starter')"
                        type="button"
                        class="w-full py-2.5 rounded-xl border border-[#D0D5DD] bg-white hover:bg-[#F9FAFB] text-xs font-semibold text-[#344054] transition-all"
                    >
                        Switch to Starter
                    </button>
                @endif
            </div>

            <!-- Pro Agency -->
            <div class="rounded-2xl border-2 p-6 space-y-5 flex flex-col justify-between relative {{ $user->plan === 'pro' ? 'border-indigo-600 bg-indigo-50/40 shadow-md' : 'border-indigo-200 bg-white' }}">
                <span class="absolute -top-3 right-4 px-2.5 py-0.5 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-extrabold text-[9px] uppercase tracking-wider">POPULAR</span>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Pro Agency</span>
                        @if($user->plan === 'pro')
                            <span class="px-2.5 py-0.5 rounded-full bg-indigo-600 text-white font-extrabold text-[10px]">CURRENT</span>
                        @endif
                    </div>
                    <p class="text-3xl font-extrabold text-[#101828]">$79 <span class="text-xs text-[#667085] font-normal">/mo</span></p>
                    <ul class="text-xs text-[#475467] space-y-2 pt-2">
                        <li>✓ 25 Active Websites</li>
                        <li>✓ Unlimited Rewrites</li>
                        <li>✓ 50+ Site Log Filter</li>
                        <li>✓ Priority Groq Llama 3.3</li>
                    </ul>
                </div>

                @if($user->plan !== 'pro')
                    <button
                        wire:click="switchPlan('pro')"
                        type="button"
                        class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-all"
                    >
                        Upgrade to Pro ($79/mo)
                    </button>
                @endif
            </div>

            <!-- Enterprise -->
            <div class="rounded-2xl border p-6 space-y-5 flex flex-col justify-between {{ $user->plan === 'enterprise' ? 'border-purple-600 bg-purple-50/30' : 'border-[#EAECF0] bg-white' }}">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Enterprise</span>
                        @if($user->plan === 'enterprise')
                            <span class="px-2.5 py-0.5 rounded-full bg-purple-600 text-white font-extrabold text-[10px]">CURRENT</span>
                        @endif
                    </div>
                    <p class="text-3xl font-extrabold text-[#101828]">$199 <span class="text-xs text-[#667085] font-normal">/mo</span></p>
                    <ul class="text-xs text-[#475467] space-y-2 pt-2">
                        <li>✓ Unlimited Websites</li>
                        <li>✓ 1-Minute Cron Frequency</li>
                        <li>✓ Custom AI Prompts</li>
                        <li>✓ Dedicated SLA & Manager</li>
                    </ul>
                </div>

                @if($user->plan !== 'enterprise')
                    <button
                        wire:click="switchPlan('enterprise')"
                        type="button"
                        class="w-full py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs shadow-xs transition-all"
                    >
                        Upgrade to Enterprise
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
