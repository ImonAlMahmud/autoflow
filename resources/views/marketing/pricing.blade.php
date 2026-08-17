<x-marketing-layout>

<!-- HERO -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F0FDF4] via-white to-white pt-20 pb-16 sm:pt-28 sm:pb-20">
    <div class="absolute top-0 right-0 w-96 h-80 bg-[#DCFCE7] rounded-full blur-3xl opacity-40 translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-5">
        <div data-reveal class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#DCFCE7] border border-[#BBF7D0] text-[#15803D] text-xs font-bold tracking-wide shadow-xs">
            <i class="fa-solid fa-tag"></i>Simple, Transparent Pricing
        </div>
        <h1 data-reveal class="text-4xl sm:text-6xl font-extrabold text-[#0F172A] tracking-tight leading-tight">
            Plans for every team size
        </h1>
        <p data-reveal class="text-lg text-[#64748B] max-w-2xl mx-auto leading-relaxed">
            Start free. Scale confidently. Every plan includes the core Autoflow engine — pick the limits that match your agency's growth.
        </p>
        <div data-reveal class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#F0FDF4] border border-[#DCFCE7] text-[#15803D] text-xs font-bold shadow-xs">
            <i class="fa-solid fa-shield-halved text-[#22C55E]"></i>14-day free trial on all plans · No credit card required
        </div>
    </div>
</section>

<!-- PRICING CARDS -->
<section class="py-12 sm:py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <!-- STARTER -->
            <div class="card-hover p-8 rounded-3xl bg-white border border-[#E2E8F0] shadow-sm space-y-7">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center"><i class="fa-solid fa-seedling text-[#15803D]"></i></div>
                        <span class="text-xs font-bold text-[#64748B] uppercase tracking-widest">Starter</span>
                    </div>
                    <div class="flex items-end gap-2">
                        <span class="text-5xl font-extrabold text-[#0F172A]">$29</span>
                        <span class="text-[#64748B] text-sm font-medium pb-1">/month</span>
                    </div>
                    <p class="text-[#64748B] text-sm mt-3 leading-relaxed">Perfect for freelancers and small agencies managing a handful of client websites.</p>
                </div>
                <a href="{{ route('register') }}?plan=starter" class="block w-full text-center px-6 py-3 rounded-2xl border-2 border-[#CBD5E1] hover:border-[#22C55E] text-[#0F172A] hover:text-[#15803D] font-bold text-sm transition-all shadow-xs">
                    <i class="fa-solid fa-arrow-right mr-1.5"></i>Start Free Trial
                </a>
                <div class="space-y-3 pt-2 border-t border-gray-100">
                    <p class="text-[11px] font-bold text-[#64748B] uppercase tracking-wider"><i class="fa-solid fa-list-check mr-1"></i>What's included</p>
                    @php $starterFeatures = [
                        ['fa-globe', 'text-[#22C55E]', '3 Active Websites', true],
                        ['fa-brain', 'text-[#22C55E]', '100 AI Content Rewrites/mo', true],
                        ['fa-code-branch', 'text-[#22C55E]', 'Git Sync & Auto-push', true],
                        ['fa-eye', 'text-[#22C55E]', 'Manual Approval Mode', true],
                        ['fa-envelope', 'text-[#22C55E]', 'Email Notifications', true],
                        ['fa-shield-halved', 'text-[#22C55E]', 'Brand Protection Governance', true],
                        ['fa-infinity', 'text-gray-300', 'Unlimited AI Rewrites', false],
                        ['fa-bolt', 'text-gray-300', 'Priority Groq Inference', false],
                        ['fa-clock', 'text-gray-300', '1-Min Cron Frequency', false],
                    ]; @endphp
                    @foreach($starterFeatures as [$icon, $iconColor, $label, $included])
                    <div class="flex items-center gap-3 text-sm {{ $included ? 'text-[#0F172A]' : 'text-gray-300 line-through' }}">
                        <i class="fa-solid {{ $icon }} {{ $iconColor }} text-xs w-4 text-center"></i>{{ $label }}
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- PRO (Featured) -->
            <div class="relative card-hover p-8 rounded-3xl bg-[#0F172A] text-white shadow-2xl shadow-slate-900/30 space-y-7 sm:scale-[1.02] border-2 border-[#22C55E]">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <div class="px-4 py-1.5 rounded-full bg-[#22C55E] text-white font-extrabold text-xs uppercase tracking-widest shadow-lg flex items-center gap-1.5">
                        <i class="fa-solid fa-crown text-xs"></i>Most Popular
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center"><i class="fa-solid fa-building text-[#22C55E]"></i></div>
                        <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Pro Agency</span>
                    </div>
                    <div class="flex items-end gap-2">
                        <span class="text-5xl font-extrabold text-white">$79</span>
                        <span class="text-gray-400 text-sm font-medium pb-1">/month</span>
                    </div>
                    <p class="text-gray-300 text-sm mt-3 leading-relaxed">Built for digital agencies managing 10–25 client websites with high-volume AI content automation.</p>
                </div>
                <a href="{{ route('register') }}?plan=pro" class="block w-full text-center px-6 py-3.5 rounded-2xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-sm transition-all shadow-lg hover:scale-105">
                    <i class="fa-solid fa-rocket mr-1.5"></i>Start Free Trial
                </a>
                <div class="space-y-3 pt-2 border-t border-slate-800">
                    <p class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider"><i class="fa-solid fa-list-check mr-1"></i>What's included</p>
                    @php $proFeatures = [
                        ['fa-globe', '25 Active Websites', true],
                        ['fa-infinity', 'Unlimited AI Rewrites', true],
                        ['fa-code-branch', 'Git Sync & Auto-push', true],
                        ['fa-eye', 'Manual Approval Mode', true],
                        ['fa-envelope', 'Email Notifications', true],
                        ['fa-shield-halved', 'Brand Protection Governance', true],
                        ['fa-bolt', 'Priority Groq Llama 3.3 70B', true],
                        ['fa-clock', '5-Min Cron Frequency', true],
                        ['fa-user-tie', 'Dedicated Account Manager', false],
                    ]; @endphp
                    @foreach($proFeatures as [$icon, $label, $included])
                    <div class="flex items-center gap-3 text-sm {{ $included ? 'text-white' : 'text-gray-500 line-through' }}">
                        <i class="fa-solid {{ $icon }} {{ $included ? 'text-[#22C55E]' : 'text-gray-600' }} text-xs w-4 text-center"></i>{{ $label }}
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- ENTERPRISE -->
            <div class="card-hover p-8 rounded-3xl bg-white border border-gray-100 shadow-sm space-y-7">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-9 h-9 rounded-xl bg-violet-100 flex items-center justify-center"><i class="fa-solid fa-city text-violet-600"></i></div>
                        <span class="text-xs font-bold text-violet-600 uppercase tracking-widest">Enterprise</span>
                    </div>
                    <div class="flex items-end gap-2">
                        <span class="text-5xl font-extrabold text-gray-900">$199</span>
                        <span class="text-gray-400 text-sm font-medium pb-1">/month</span>
                    </div>
                    <p class="text-gray-400 text-sm mt-3 leading-relaxed">For large teams and enterprises needing unlimited scale, dedicated SLA, and custom AI configuration.</p>
                </div>
                <a href="{{ route('contact') }}" class="block w-full text-center px-6 py-3 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white font-bold text-sm transition-all shadow-lg shadow-violet-500/20">
                    <i class="fa-solid fa-phone mr-1.5"></i>Contact Sales
                </a>
                <div class="space-y-3 pt-2 border-t border-gray-50">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider"><i class="fa-solid fa-circle-plus mr-1"></i>Everything in Pro, plus</p>
                    @php $enterpriseFeatures = [
                        ['fa-infinity', 'Unlimited Websites'],
                        ['fa-clock', '1-Minute Cron Frequency'],
                        ['fa-sliders', 'Custom AI System Prompts'],
                        ['fa-user-tie', 'Dedicated Account Manager'],
                        ['fa-server', 'Private Self-hosted Deployment'],
                        ['fa-file-contract', 'Custom SLA & Uptime Guarantee'],
                        ['fa-users', 'Multi-user Team Seats'],
                        ['fa-headset', 'Priority 24/7 Support'],
                    ]; @endphp
                    @foreach($enterpriseFeatures as [$icon, $label])
                    <div class="flex items-center gap-3 text-sm text-gray-700">
                        <i class="fa-solid {{ $icon }} text-violet-500 text-xs w-4 text-center"></i>{{ $label }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-24 sm:py-32 bg-gray-50 border-t border-gray-100">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div data-reveal class="text-center space-y-3">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100 text-[#15803D] text-xs font-bold tracking-wide mb-2">
                <i class="fa-solid fa-circle-question"></i>FAQ
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">Frequently Asked Questions</h2>
            <p class="text-gray-400">Can't find your answer? <a href="{{ route('contact') }}" class="text-[#15803D] hover:text-[#22C55E] font-semibold underline">Contact our team <i class="fa-solid fa-arrow-right text-xs"></i></a></p>
        </div>

        <div class="space-y-3" x-data="{ open: null }">
            @php $faqs = [
                ['Does Autoflow break my website CSS or design?', 'Never. Autoflow performs surgical text-node replacement using PHP string matching. It never touches CSS classes, inline styles, gradient definitions, or any HTML attributes. Your visual design is completely untouched.'],
                ['Can I use Autoflow on a shared cPanel hosting?', 'Yes. Autoflow is designed to run on standard LAMP/LEMP shared hosting. You can configure cron jobs directly from cPanel, and Git operations use HTTPS PAT authentication — no SSH required.'],
                ['What AI model powers the content rewriting?', "Autoflow uses Groq's inference API with the Llama 3.3 70B Versatile model. Groq provides sub-2-second response times, making batch page rewrites extremely fast and cost-efficient."],
                ['Can I review AI changes before they go live?', 'Absolutely. Each website has an "Approval Mode" setting. In Manual Review mode, all rewrites are queued for your approval before git commit and push. You see before/after diffs in the dashboard.'],
                ['What happens if the AI rewrite fails?', 'Autoflow has built-in retry logic. If a Groq API call fails or produces invalid output, the original HTML is preserved unchanged and the job is logged as failed. No partial rewrites are ever committed.'],
                ['Is my website source code stored on your servers?', "No. Autoflow connects to your own Git repository and works within the file system of the server it's installed on. For self-hosted (Enterprise) plans, everything runs in your own infrastructure."],
            ]; @endphp

            @foreach($faqs as $i => [$question, $answer])
            <div class="rounded-2xl bg-white border border-gray-100 shadow-sm overflow-hidden">
                <button @click="open === {{ $i }} ? open = null : open = {{ $i }}" class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-gray-50 transition-colors">
                    <span class="text-sm font-bold text-gray-900"><i class="fa-solid fa-circle-question text-indigo-400 mr-2"></i>{{ $question }}</span>
                    <i class="fa-solid fa-plus text-gray-400 flex-shrink-0 transition-transform" :class="open === {{ $i }} ? 'rotate-45 text-[#22C55E]' : ''"></i>
                </button>
                <div x-show="open === {{ $i }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-5 text-sm text-gray-500 leading-relaxed border-t border-gray-50 pt-4">
                    <i class="fa-solid fa-circle-check text-emerald-500 mr-1.5"></i>{{ $answer }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

</x-marketing-layout>
