<x-marketing-layout title="Pricing Plans — Autoflow by Ideomet Technologies">
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <div class="text-center space-y-4 max-w-3xl mx-auto">
                <span class="px-3.5 py-1 rounded-full bg-emerald-950 text-emerald-400 border border-emerald-800/60 text-xs font-semibold">
                    Transparent SaaS Pricing
                </span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">
                    Simple Plans for Agencies & Enterprises
                </h1>
                <p class="text-slate-400 text-base">
                    Scale your website automation without hidden fees. Built and backed by Ideomet Technologies.
                </p>
            </div>

            <!-- Pricing Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                <!-- Starter Plan -->
                <div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 flex flex-col justify-between space-y-6 hover:border-slate-700 transition-all">
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-white">Starter</h3>
                        <p class="text-xs text-slate-400">Perfect for single website owners and small business sites.</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-extrabold text-white">$29</span>
                            <span class="text-xs text-slate-400">/ month</span>
                        </div>
                        <ul class="space-y-3 pt-4 border-t border-slate-800 text-xs text-slate-300">
                            <li class="flex items-center gap-2">✓ 3 Active Websites</li>
                            <li class="flex items-center gap-2">✓ Up to 100 AI Rewrites / mo</li>
                            <li class="flex items-center gap-2">✓ Style & Gradient Preservation</li>
                            <li class="flex items-center gap-2">✓ Basic Git Push Automation</li>
                        </ul>
                    </div>
                    <a href="{{ route('contact') }}" class="w-full py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs text-center transition-all">
                        Get Started
                    </a>
                </div>

                <!-- Pro Plan (Featured) -->
                <div class="p-8 rounded-3xl bg-gradient-to-b from-indigo-950/90 to-slate-900/90 border-2 border-indigo-500 flex flex-col justify-between space-y-6 relative shadow-2xl shadow-indigo-600/20">
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-indigo-600 text-white font-bold text-[10px] uppercase tracking-wider">
                        Most Popular
                    </div>
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-white">Pro Agency</h3>
                        <p class="text-xs text-indigo-200">Built for agencies managing multiple client websites.</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-extrabold text-white">$79</span>
                            <span class="text-xs text-indigo-300">/ month</span>
                        </div>
                        <ul class="space-y-3 pt-4 border-t border-indigo-900/80 text-xs text-slate-200">
                            <li class="flex items-center gap-2">✓ Up to 25 Active Websites</li>
                            <li class="flex items-center gap-2">✓ Unlimited AI Rewrites</li>
                            <li class="flex items-center gap-2">✓ Site-Wise Log Filtering (50+ sites)</li>
                            <li class="flex items-center gap-2">✓ Instant Custom Schedule Timers</li>
                            <li class="flex items-center gap-2">✓ Priority Groq Llama 3.3 API</li>
                        </ul>
                    </div>
                    <a href="{{ route('contact') }}" class="w-full py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs text-center shadow-lg transition-all">
                        Start Pro Trial →
                    </a>
                </div>

                <!-- Enterprise Plan -->
                <div class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 flex flex-col justify-between space-y-6 hover:border-slate-700 transition-all">
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-white">Enterprise</h3>
                        <p class="text-xs text-slate-400">Custom deployment & SLA for high-volume networks.</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-extrabold text-white">$199</span>
                            <span class="text-xs text-slate-400">/ month</span>
                        </div>
                        <ul class="space-y-3 pt-4 border-t border-slate-800 text-xs text-slate-300">
                            <li class="flex items-center gap-2">✓ Unlimited Managed Websites</li>
                            <li class="flex items-center gap-2">✓ Dedicated Ideomet Account Manager</li>
                            <li class="flex items-center gap-2">✓ Custom API & GitHub Webhooks</li>
                            <li class="flex items-center gap-2">✓ 99.9% Uptime SLA Guarantee</li>
                        </ul>
                    </div>
                    <a href="{{ route('contact') }}" class="w-full py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs text-center transition-all">
                        Contact Sales
                    </a>
                </div>
            </div>

        </div>
    </section>
</x-marketing-layout>
