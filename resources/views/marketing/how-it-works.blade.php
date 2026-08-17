<x-marketing-layout>

<!-- HERO -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#F0FDF4] via-white to-white pt-20 pb-16 sm:pt-28 sm:pb-20">
    <div class="absolute top-0 right-0 w-96 h-80 bg-[#DCFCE7] rounded-full blur-3xl opacity-40 translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-5">
        <div data-reveal class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#DCFCE7] border border-[#BBF7D0] text-[#15803D] text-xs font-bold tracking-wide shadow-xs">
            <i class="fa-solid fa-gears"></i>Platform Architecture & Autonomous Workflow
        </div>
        <h1 data-reveal class="text-4xl sm:text-6xl font-extrabold text-[#0F172A] tracking-tight leading-tight">
            How Autoflow Works
        </h1>
        <p data-reveal class="text-lg text-[#64748B] max-w-2xl mx-auto leading-relaxed">
            A seamlessly automated pipeline that connects your website repository, runs Groq AI rewrites, and deploys to GitHub — all without human intervention.
        </p>
    </div>
</section>

<!-- PIPELINE VISUAL ILLUSTRATION -->
<section class="py-6 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div data-reveal class="rounded-3xl overflow-hidden shadow-xl border border-[#E2E8F0] p-4 sm:p-8 bg-[#F8FAFC]">
            <img src="{{ asset('images/workflow_pipeline.jpg') }}" alt="Autoflow Pipeline Visual Steps" class="w-full h-auto object-cover rounded-2xl" />
        </div>
    </div>
</section>

<!-- WORKFLOW STEPS -->
<section class="py-16 sm:py-24 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- 3-step row -->
        <div data-reveal-stagger class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 items-start mb-8 sm:mb-12">

            <!-- Step 1 -->
            <div class="flex flex-col items-center text-center space-y-4 group">
                <div class="relative">
                    <div class="w-[72px] h-[72px] rounded-2xl bg-[#22C55E] flex items-center justify-center text-white shadow-xl shadow-green-500/20 group-hover:scale-110 transition-all duration-300">
                        <i class="fa-solid fa-link text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white border-2 border-[#22C55E] text-[10px] font-extrabold text-[#15803D] flex items-center justify-center shadow-sm">1</div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Connect Website</h3>
                    <p class="text-xs text-[#64748B] mt-1 leading-relaxed">Point Autoflow to your local folder or GitHub repository. Supports HTTPS PAT and SSH auth.</p>
                </div>
                <div class="px-3 py-1.5 rounded-lg bg-[#F0FDF4] border border-[#DCFCE7] text-[10px] font-mono text-[#15803D]">Local Path / Git URL</div>
            </div>

            <!-- Arrow -->
            <div class="hidden lg:flex items-center justify-center pt-8">
                <i class="fa-solid fa-arrow-right text-gray-300 text-xl"></i>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col items-center text-center space-y-4 group">
                <div class="relative">
                    <div class="w-[72px] h-[72px] rounded-2xl bg-[#16A34A] flex items-center justify-center text-white shadow-xl shadow-green-600/20 group-hover:scale-110 transition-all duration-300">
                        <i class="fa-solid fa-brain text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white border-2 border-[#16A34A] text-[10px] font-extrabold text-[#15803D] flex items-center justify-center shadow-sm">2</div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Universal AI Scanning</h3>
                    <p class="text-xs text-[#64748B] mt-1 leading-relaxed">Runs via Groq, OpenAI, Claude, Gemini, OpenRouter, or your own local Ollama server.</p>
                </div>
                <div class="px-3 py-1.5 rounded-lg bg-[#F0FDF4] border border-[#DCFCE7] text-[10px] font-mono text-[#15803D]">Cloud API / Local Ollama</div>
            </div>

            <!-- Arrow -->
            <div class="hidden lg:flex items-center justify-center pt-8">
                <i class="fa-solid fa-arrow-right text-gray-300 text-xl"></i>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col items-center text-center space-y-4 group">
                <div class="relative">
                    <div class="w-[72px] h-[72px] rounded-2xl bg-[#15803D] flex items-center justify-center text-white shadow-xl shadow-green-700/20 group-hover:scale-110 transition-all duration-300">
                        <i class="fa-solid fa-paintbrush text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white border-2 border-[#15803D] text-[10px] font-extrabold text-[#15803D] flex items-center justify-center shadow-sm">3</div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">CSS-Safe Patching</h3>
                    <p class="text-xs text-[#64748B] mt-1 leading-relaxed">Rewritten text is surgically patched into the HTML source. Zero changes to CSS classes, inline styles, or layout attributes.</p>
                </div>
                <div class="px-3 py-1.5 rounded-lg bg-[#F0FDF4] border border-[#DCFCE7] text-[10px] font-mono text-[#15803D]">str_replace() → DOM-safe</div>
            </div>
        </div>

        <!-- Divider -->
        <div class="flex items-center gap-4 my-6">
            <div class="flex-1 h-px bg-gray-100"></div>
            <div class="flex items-center gap-2 text-xs text-[#64748B] font-medium px-3">
                <i class="fa-solid fa-arrow-down text-[#22C55E]"></i>Continues automatically
            </div>
            <div class="flex-1 h-px bg-gray-100"></div>
        </div>

        <!-- 3-step row 2 -->
        <div data-reveal-stagger class="grid grid-cols-2 sm:grid-cols-3 gap-6 max-w-3xl mx-auto">

            <!-- Step 4 -->
            <div class="flex flex-col items-center text-center space-y-4 group">
                <div class="relative">
                    <div class="w-[72px] h-[72px] rounded-2xl bg-[#22C55E] flex items-center justify-center text-white shadow-xl shadow-green-500/20 group-hover:scale-110 transition-all duration-300">
                        <i class="fa-solid fa-shield-halved text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white border-2 border-[#22C55E] text-[10px] font-extrabold text-[#15803D] flex items-center justify-center shadow-sm">4</div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Brand Validation</h3>
                    <p class="text-xs text-[#64748B] mt-1 leading-relaxed">Protected terms, exclusion selectors, and approval mode rules are applied before any commit is made.</p>
                </div>
                <div class="px-3 py-1.5 rounded-lg bg-[#F0FDF4] border border-[#DCFCE7] text-[10px] font-mono text-[#15803D]"><i class="fa-solid fa-check mr-1"></i>Governance Engine</div>
            </div>

            <!-- Step 5 -->
            <div class="flex flex-col items-center text-center space-y-4 group">
                <div class="relative">
                    <div class="w-[72px] h-[72px] rounded-2xl bg-[#0F172A] flex items-center justify-center text-white shadow-xl shadow-slate-900/20 group-hover:scale-110 transition-all duration-300">
                        <i class="fa-brands fa-github text-2xl text-[#22C55E]"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white border-2 border-[#0F172A] text-[10px] font-extrabold text-[#0F172A] flex items-center justify-center shadow-sm">5</div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Auto Git Push</h3>
                    <p class="text-xs text-[#64748B] mt-1 leading-relaxed">git add → git commit → git push. Fully automated with your configured author identity and target branch.</p>
                </div>
                <div class="px-3 py-1.5 rounded-lg bg-[#F0FDF4] border border-[#DCFCE7] text-[10px] font-mono text-[#15803D]">git push origin main</div>
            </div>

            <!-- Step 6 -->
            <div class="flex flex-col items-center text-center space-y-4 group">
                <div class="relative">
                    <div class="w-[72px] h-[72px] rounded-2xl bg-[#16A34A] flex items-center justify-center text-white shadow-xl shadow-green-600/20 group-hover:scale-110 transition-all duration-300">
                        <i class="fa-solid fa-bell text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white border-2 border-[#16A34A] text-[10px] font-extrabold text-[#15803D] flex items-center justify-center shadow-sm">6</div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#0F172A]">Notify & Repeat</h3>
                    <p class="text-xs text-[#64748B] mt-1 leading-relaxed">Email alerts sent to your notification receiver. The cron scheduler queues the next cycle automatically.</p>
                </div>
                <div class="px-3 py-1.5 rounded-lg bg-[#F0FDF4] border border-[#DCFCE7] text-[10px] font-mono text-[#15803D]">SMTP → Next Cron Tick</div>
            </div>
        </div>
    </div>
</section>

<!-- TECH DEEP DIVE -->
<section class="py-24 bg-[#F8FAFC] border-y border-[#E2E8F0]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-reveal class="space-y-8">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#DCFCE7] text-[#15803D] text-xs font-bold tracking-wide">
                        <i class="fa-solid fa-code"></i>Technical Architecture
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0F172A]">Built for reliability<br>at enterprise scale</h2>
                    <p class="text-[#64748B] leading-relaxed">Autoflow is a Laravel 11 + Livewire 3 application with an isolated job pipeline. Each page rewrite runs with timeout protection, active validation, and comprehensive error logging.</p>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs">
                        <div class="w-10 h-10 rounded-xl bg-[#F0FDF4] flex items-center justify-center text-[#15803D] shrink-0 font-bold text-sm">01</div>
                        <div><h4 class="text-sm font-bold text-[#0F172A]">Safe DOM Parsing & Selective Replacement</h4><p class="text-xs text-[#64748B] mt-0.5">Autoflow extracts human-readable copy only. CSS stylesheets, animation classes, JavaScript handlers, and structural HTML divs remain 100% untouched.</p></div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs">
                        <div class="w-10 h-10 rounded-xl bg-[#F0FDF4] flex items-center justify-center text-[#15803D] shrink-0 font-bold text-sm">02</div>
                        <div><h4 class="text-sm font-bold text-[#0F172A]">Smart Multi-Page Queue & Rate Throttling</h4><p class="text-xs text-[#64748B] mt-0.5">When refreshing 10, 50, or 100+ pages, jobs are processed in controlled async batches to avoid API rate limits and ensure maximum semantic quality on every page.</p></div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-[#E2E8F0] shadow-2xs">
                        <div class="w-10 h-10 rounded-xl bg-[#F0FDF4] flex items-center justify-center text-[#15803D] shrink-0 font-bold text-sm">03</div>
                        <div><h4 class="text-sm font-bold text-[#0F172A]">Atomic Git Commits & Instant Rollbacks</h4><p class="text-xs text-[#64748B] mt-0.5">Batch updates are grouped into single atomic Git commits with complete visual diff tracking, branch protection, and single-click rollbacks.</p></div>
                    </div>
                </div>
            </div>

            <!-- Code Card -->
            <div data-reveal class="relative">
                <div class="absolute inset-0 bg-green-500/5 rounded-3xl blur-3xl"></div>
                <div class="relative bg-[#0F172A] border border-slate-700 rounded-2xl overflow-hidden shadow-2xl">
                    <div class="flex items-center gap-2 px-5 py-3 bg-slate-800 border-b border-slate-700">
                        <div class="flex gap-1.5"><div class="w-3 h-3 rounded-full bg-rose-500/70"></div><div class="w-3 h-3 rounded-full bg-amber-500/70"></div><div class="w-3 h-3 rounded-full bg-emerald-500/70"></div></div>
                        <span class="text-[11px] text-gray-400 font-mono ml-2"><i class="fa-regular fa-file-code mr-1"></i>JobExecutionService.php</span>
                    </div>
                    <div class="p-5 font-mono text-[11px] leading-relaxed space-y-1 overflow-x-auto">
                        <div><span class="text-emerald-400">$prompt</span> <span class="text-gray-400">=</span> <span class="text-[#22C55E]">"Rewrite this website copy..."</span><span class="text-gray-400">;</span></div>
                        <div class="pl-4 text-gray-400">// Model: Groq Llama 3.3 70B (JSON mode)</div>
                        <div><span class="text-emerald-400">$res</span> <span class="text-gray-400">=</span> <span class="text-blue-400">Http</span><span class="text-gray-400">::</span><span class="text-yellow-300">post</span><span class="text-gray-400">(</span><span class="text-[#22C55E]">'groq/chat'</span><span class="text-gray-400">, [...]);</span></div>
                        <div class="mt-2 text-gray-500">// Surgical DOM Text Replacement</div>
                        <div><span class="text-emerald-400">$html</span> <span class="text-gray-400">=</span> <span class="text-yellow-300">str_replace</span><span class="text-gray-400">(</span><span class="text-purple-300">$oldSegment</span><span class="text-gray-400">, </span><span class="text-purple-300">$newSegment</span><span class="text-gray-400">, </span><span class="text-purple-300">$html</span><span class="text-gray-400">);</span></div>
                        <div class="mt-2 text-gray-500">// Git Commit & Remote Push</div>
                        <div><span class="text-yellow-300">shell_exec</span><span class="text-gray-400">(</span><span class="text-[#22C55E]">"git add . && git commit && git push"</span><span class="text-gray-400">);</span></div>
                        <div class="mt-2 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-[#22C55E] pulse-dot inline-block"></span><span class="text-[#22C55E]">// Job complete. Live website updated.</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-24 bg-white text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <h2 class="text-4xl font-extrabold text-[#0F172A]"><i class="fa-solid fa-rocket text-[#22C55E] mr-2"></i>Ready to automate?</h2>
        <p class="text-[#64748B] text-lg">Set up your first website in under 5 minutes. No code. No complex servers. Just connect and go.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}" class="px-8 py-4 rounded-2xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-sm shadow-xl shadow-green-500/20 transition-all hover:scale-[1.03] flex items-center gap-2"><i class="fa-solid fa-rocket"></i>Start Free Trial</a>
            <a href="{{ route('pricing') }}" class="px-8 py-4 rounded-2xl border border-[#CBD5E1] hover:border-[#22C55E] text-[#0F172A] hover:text-[#15803D] font-bold text-sm transition-all bg-white flex items-center gap-2 shadow-xs"><i class="fa-solid fa-tag text-[#22C55E]"></i>View Pricing</a>
        </div>
    </div>
</section>

</x-marketing-layout>
