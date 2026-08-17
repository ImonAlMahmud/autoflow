<x-marketing-layout>

<!-- ======================== HERO ======================== -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F0FDF4] via-white to-white pt-20 pb-20 sm:pt-28 sm:pb-28">
    <!-- Decorative top blobs -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-[#DCFCE7] rounded-full blur-3xl opacity-50 -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-[#F0FDF4] rounded-full blur-3xl opacity-60 translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-4xl mx-auto space-y-7">

            <!-- Badge -->
            <div data-reveal class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-[#DCFCE7] border border-[#BBF7D0] text-[#15803D] text-xs font-bold tracking-wide shadow-xs">
                <span class="w-2 h-2 rounded-full bg-[#22C55E] pulse-dot inline-block"></span>
                <i class="fa-solid fa-bolt text-[#15803D]"></i>
                Autoflow 2026 — Autonomous AI Website Refresh Engine
            </div>

            <!-- Headline -->
            <h1 data-reveal class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-[#0F172A] tracking-tight leading-[1.08]">
                Your Website,
                <span class="gradient-text"> Always Fresh.</span>
                <br>Automatically.
            </h1>

            <!-- Subheadline -->
            <p data-reveal class="text-lg sm:text-xl text-[#64748B] max-w-3xl mx-auto leading-relaxed font-normal">
                Autoflow connects to your website, connects with <strong class="text-[#0F172A] font-bold">all popular AI providers (Groq, OpenAI, Anthropic Claude, Google Gemini, OpenRouter) & Local Server LLMs (Ollama)</strong> to intelligently rewrite stale content, preserves every CSS style & HTML layout, and auto-pushes to GitHub — on your exact schedule.
            </p>

            <!-- CTAs -->
            <div data-reveal class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                <a href="{{ route('register') }}" class="group px-8 py-4 rounded-2xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-sm shadow-xl shadow-green-500/20 transition-all hover:scale-[1.03] flex items-center gap-2.5">
                    <i class="fa-solid fa-rocket"></i>
                    Start Free Trial
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-0.5 transition-transform text-xs"></i>
                </a>
                <a href="{{ route('how-it-works') }}" class="px-8 py-4 rounded-2xl border border-[#CBD5E1] hover:border-[#22C55E] text-[#0F172A] hover:text-[#15803D] font-bold text-sm transition-all bg-white shadow-sm hover:shadow-md flex items-center gap-2">
                    <i class="fa-regular fa-circle-play text-base text-[#22C55E]"></i>
                    See How It Works
                </a>
            </div>

            <!-- Trust pills -->
            <div data-reveal class="flex flex-wrap items-center justify-center gap-5 pt-3 text-[#64748B] text-xs font-semibold">
                <div class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-[#22C55E]"></i>Cloud & Local Ollama AI</div>
                <div class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-[#22C55E]"></i>CSS layout preserved</div>
                <div class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-[#22C55E]"></i>Auto Git Push & Deploy</div>
                <div class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-[#22C55E]"></i>14-day free trial</div>
            </div>
        </div>

        <!-- Dashboard Image Preview -->
        <div data-reveal class="mt-14 max-w-5xl mx-auto">
            <div class="relative bg-white rounded-3xl shadow-2xl shadow-green-900/10 border border-[#E2E8F0] overflow-hidden group">
                <img src="{{ asset('images/hero_dashboard.jpg') }}" alt="Autoflow AI Live Dashboard & Pipeline" class="w-full h-auto object-cover transform transition-transform duration-500 group-hover:scale-[1.01]" />
                
                <div class="absolute bottom-4 left-4 right-4 bg-white/95 backdrop-blur-md border border-[#E2E8F0] rounded-2xl p-3.5 shadow-lg flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#22C55E] pulse-dot"></span>
                        <span class="font-bold text-[#0F172A]">Universal AI Pipeline Active</span>
                        <span class="text-[#64748B] hidden md:inline">| Groq, OpenAI, Claude, Gemini & Local Ollama</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg bg-[#F0FDF4] text-[#15803D] font-mono font-bold text-[11px] border border-[#DCFCE7]">
                            <i class="fa-brands fa-github mr-1"></i> main branch synced
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================== TRUSTED BY ======================== -->
<section class="py-14 border-y border-[#E2E8F0] bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-[#64748B] text-xs font-bold uppercase tracking-widest mb-8">
            <i class="fa-solid fa-layer-group mr-2 text-[#22C55E]"></i>Supported Universal AI Providers & Local Engines
        </p>
        <div class="flex flex-wrap items-center justify-center gap-x-12 gap-y-4 text-xs font-bold text-[#0F172A]">
            <span class="px-4 py-2 rounded-xl bg-white border border-[#E2E8F0] shadow-2xs flex items-center gap-2"><i class="fa-solid fa-server text-[#22C55E]"></i>Ollama (Local Server)</span>
            <span class="px-4 py-2 rounded-xl bg-white border border-[#E2E8F0] shadow-2xs flex items-center gap-2"><i class="fa-solid fa-bolt text-[#15803D]"></i>Groq Cloud (Llama 3.3)</span>
            <span class="px-4 py-2 rounded-xl bg-white border border-[#E2E8F0] shadow-2xs flex items-center gap-2"><i class="fa-solid fa-brain text-[#0F172A]"></i>OpenAI / GPT-4o</span>
            <span class="px-4 py-2 rounded-xl bg-white border border-[#E2E8F0] shadow-2xs flex items-center gap-2"><i class="fa-solid fa-feather text-[#D97706]"></i>Anthropic Claude</span>
            <span class="px-4 py-2 rounded-xl bg-white border border-[#E2E8F0] shadow-2xs flex items-center gap-2"><i class="fa-solid fa-gem text-[#3B82F6]"></i>Google Gemini</span>
            <span class="px-4 py-2 rounded-xl bg-white border border-[#E2E8F0] shadow-2xs flex items-center gap-2"><i class="fa-solid fa-network-wired text-[#8B5CF6]"></i>OpenRouter</span>
        </div>
    </div>
</section>

<!-- ======================== FEATURES ======================== -->
<section class="py-24 sm:py-32 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div data-reveal class="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#DCFCE7] text-[#15803D] text-xs font-bold tracking-wide">
                <i class="fa-solid fa-star"></i>Core Platform Features
            </div>
            <h2 class="text-4xl sm:text-5xl font-extrabold text-[#0F172A] leading-tight">Everything you need for<br><span class="gradient-text">autonomous website refresh</span></h2>
            <p class="text-[#64748B] text-lg leading-relaxed">From multi-provider AI content generation (Cloud & Local) to bulletproof Git automation — Autoflow handles the entire lifecycle.</p>
        </div>

        <div data-reveal-stagger class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="card-hover group p-7 rounded-3xl bg-white border border-[#E2E8F0] shadow-sm hover:border-[#22C55E]">
                <div class="w-12 h-12 rounded-2xl bg-[#DCFCE7] flex items-center justify-center text-[#15803D] mb-5">
                    <i class="fa-solid fa-network-wired text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-[#0F172A] mb-2">Universal AI Engine (Cloud + Local)</h3>
                <p class="text-[#64748B] text-sm leading-relaxed">Connect to any popular AI provider — Groq, OpenAI GPT, Claude, Google Gemini, OpenRouter, or run 100% locally and privately with Ollama local server.</p>
                <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#15803D]"><i class="fa-solid fa-server"></i>Cloud + Local Ollama support</div>
            </div>

            <div class="card-hover group p-7 rounded-3xl bg-white border border-[#E2E8F0] shadow-sm hover:border-[#22C55E]">
                <div class="w-12 h-12 rounded-2xl bg-[#DCFCE7] flex items-center justify-center text-[#15803D] mb-5">
                    <i class="fa-solid fa-paintbrush text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-[#0F172A] mb-2">CSS-Safe HTML Rewriting</h3>
                <p class="text-[#64748B] text-sm leading-relaxed">Unlike generic AI tools, Autoflow targets only text nodes — preserving every gradient, animation, flexbox layout, and class structure. Your design is sacred.</p>
                <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#15803D]"><i class="fa-solid fa-shield-halved"></i>100% layout preservation</div>
            </div>

            <div class="card-hover group p-7 rounded-3xl bg-white border border-[#E2E8F0] shadow-sm hover:border-[#22C55E]">
                <div class="w-12 h-12 rounded-2xl bg-[#DCFCE7] flex items-center justify-center text-[#15803D] mb-5">
                    <i class="fa-brands fa-github text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-[#0F172A] mb-2">Native Git Push Automation</h3>
                <p class="text-[#64748B] text-sm leading-relaxed">Every successful rewrite is automatically staged, committed with meaningful messages, and pushed to your GitHub repository — with author attribution and branch control.</p>
                <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#15803D]"><i class="fa-solid fa-code-branch"></i>Auto-commit & push</div>
            </div>

            <div class="card-hover group p-7 rounded-3xl bg-white border border-[#E2E8F0] shadow-sm hover:border-[#22C55E]">
                <div class="w-12 h-12 rounded-2xl bg-[#DCFCE7] flex items-center justify-center text-[#15803D] mb-5">
                    <i class="fa-solid fa-layer-group text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-[#0F172A] mb-2">Smart Multi-Page Batch Handling</h3>
                <p class="text-[#64748B] text-sm leading-relaxed">Whether refreshing 5 or 50+ pages, Autoflow uses intelligent chunked queue workers and API throttling to guarantee 100% execution accuracy without timeouts.</p>
                <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#15803D]"><i class="fa-solid fa-shield-check"></i>Zero-breakage guarantee</div>
            </div>

            <div class="card-hover group p-7 rounded-3xl bg-white border border-[#E2E8F0] shadow-sm hover:border-[#22C55E]">
                <div class="w-12 h-12 rounded-2xl bg-[#DCFCE7] flex items-center justify-center text-[#15803D] mb-5">
                    <i class="fa-solid fa-lock text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-[#0F172A] mb-2">Brand Governance & Whitelisting</h3>
                <p class="text-[#64748B] text-sm leading-relaxed">Define protected brand terms, legal disclaimers, and selector exclusions. Autoflow's governance engine guarantees AI will never alter your mission-critical copy.</p>
                <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#15803D]"><i class="fa-solid fa-lock"></i>Whitelist protection</div>
            </div>

            <div class="card-hover group p-7 rounded-3xl bg-white border border-[#E2E8F0] shadow-sm hover:border-[#22C55E]">
                <div class="w-12 h-12 rounded-2xl bg-[#DCFCE7] flex items-center justify-center text-[#15803D] mb-5">
                    <i class="fa-solid fa-file-lines text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-[#0F172A] mb-2">Atomic Git Commits & Rollback</h3>
                <p class="text-[#64748B] text-sm leading-relaxed">Changes across all pages are batched into clean atomic commits with complete visual diff history, enabling instant single-click rollbacks if needed.</p>
                <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#15803D]"><i class="fa-solid fa-rotate-left"></i>Instant rollback audit</div>
            </div>
        </div>
    </div>
</section>

<!-- ======================== WORKFLOW PIPELINE SHOWCASE ======================== -->
<section class="py-20 bg-[#F8FAFC] border-t border-[#E2E8F0]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div data-reveal class="text-center max-w-3xl mx-auto mb-12 space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#DCFCE7] text-[#15803D] text-xs font-bold tracking-wide">
                <i class="fa-solid fa-microchip"></i>Autonomous Execution Architecture
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0F172A]">How Autoflow Rewrites and Commits in Seconds</h2>
            <p class="text-[#64748B] text-base leading-relaxed">From raw HTML scanning to humanized Groq AI rephrasing, semantic verification, and automated GitHub branch deployment.</p>
        </div>

        <div data-reveal class="max-w-5xl mx-auto bg-white rounded-3xl p-4 sm:p-8 border border-[#E2E8F0] shadow-card">
            <img src="{{ asset('images/workflow_pipeline.jpg') }}" alt="Autoflow AI Intelligent Pipeline" class="w-full h-auto object-cover rounded-2xl" />
        </div>
    </div>
</section>

<!-- ======================== STATS ======================== -->
<section class="py-20 bg-[#0F172A] border-y border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div data-reveal-stagger class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="space-y-2">
                <div class="text-4xl font-extrabold text-[#22C55E]">99.9<span class="text-white">%</span></div>
                <div class="text-gray-400 text-sm font-medium"><i class="fa-solid fa-paintbrush mr-1 text-[#22C55E]"></i>CSS Layout Preservation</div>
            </div>
            <div class="space-y-2">
                <div class="text-4xl font-extrabold text-[#22C55E]">&lt;2<span class="text-white">s</span></div>
                <div class="text-gray-400 text-sm font-medium"><i class="fa-solid fa-bolt mr-1 text-[#22C55E]"></i>Avg. Page Rewrite Time</div>
            </div>
            <div class="space-y-2">
                <div class="text-4xl font-extrabold text-[#22C55E]">100<span class="text-white">%</span></div>
                <div class="text-gray-400 text-sm font-medium"><i class="fa-brands fa-github mr-1 text-[#22C55E]"></i>Auto Git Push Success</div>
            </div>
            <div class="space-y-2">
                <div class="text-4xl font-extrabold text-[#22C55E]">∞</div>
                <div class="text-gray-400 text-sm font-medium"><i class="fa-solid fa-infinity mr-1 text-[#22C55E]"></i>Rewrites on Pro/Enterprise</div>
            </div>
        </div>
    </div>
</section>

<!-- ======================== CTA ======================== -->
<section class="py-24 sm:py-32 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div data-reveal class="relative p-10 sm:p-14 rounded-3xl bg-[#F0FDF4] border border-[#DCFCE7] overflow-hidden shadow-card">
            <div class="space-y-6">
                <div class="w-16 h-16 rounded-2xl bg-[#22C55E] flex items-center justify-center text-white mx-auto shadow-xl shadow-green-500/20">
                    <i class="fa-solid fa-rocket text-2xl"></i>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0F172A] tracking-tight">Start Automating Your Websites Today</h2>
                <p class="text-[#64748B] text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">Join high-performing marketing teams and web agencies using Autoflow to keep static websites fresh, lively, and SEO-ranked.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                    <a href="{{ route('register') }}" class="px-8 py-4 rounded-2xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-sm shadow-xl shadow-green-500/25 transition-all hover:scale-[1.03] flex items-center gap-2">
                        <i class="fa-solid fa-rocket"></i>Create Free Account
                    </a>
                    <a href="{{ route('pricing') }}" class="px-8 py-4 rounded-2xl border border-[#CBD5E1] hover:border-[#22C55E] text-[#0F172A] hover:text-[#15803D] font-bold text-sm transition-all bg-white shadow-xs">
                        <i class="fa-solid fa-tag mr-1.5 text-[#22C55E]"></i>View Pricing Plans
                    </a>
                </div>
                <p class="text-[#64748B] text-xs"><i class="fa-solid fa-shield-halved text-[#22C55E] mr-1"></i>14-day free trial · Instant GitHub setup · Cancel anytime</p>
            </div>
        </div>
    </div>
</section>

</x-marketing-layout>
