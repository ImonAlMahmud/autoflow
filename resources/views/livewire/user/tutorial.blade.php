<div class="space-y-8 max-w-5xl mx-auto pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">How to Use Autoflow</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#DCFCE7] text-[#15803D] border border-[#BBF7D0]">
                    Quick Start & Tutorial Guide
                </span>
            </div>
            <p class="text-xs text-[#64748B] mt-1">Learn how to connect your static websites, configure AI models, and run autonomous SEO refresh workflows.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('websites.create') }}" class="px-4 py-2.5 bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:scale-105 flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i>
                Connect Your First Website
            </a>
        </div>
    </div>

    <!-- Quick 4-Step Visual Journey -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="p-5 bg-white rounded-2xl border border-[#E2E8F0] shadow-card space-y-3 relative overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#15803D] font-black text-sm flex items-center justify-center border border-emerald-100">
                01
            </div>
            <h4 class="text-sm font-bold text-[#0F172A]">Connect GitHub Repo</h4>
            <p class="text-xs text-[#64748B] leading-relaxed">Provide your GitHub repo URL (e.g. <code>username/repo</code>). Autoflow automatically scans and discovers all your HTML pages.</p>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-[#E2E8F0] shadow-card space-y-3 relative overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#15803D] font-black text-sm flex items-center justify-center border border-emerald-100">
                02
            </div>
            <h4 class="text-sm font-bold text-[#0F172A]">One-Time Global Token</h4>
            <p class="text-xs text-[#64748B] leading-relaxed">Set your GitHub Personal Access Token once in Settings. All your connected websites will automatically use it.</p>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-[#E2E8F0] shadow-card space-y-3 relative overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#15803D] font-black text-sm flex items-center justify-center border border-emerald-100">
                03
            </div>
            <h4 class="text-sm font-bold text-[#0F172A]">Groq AI Rewrite</h4>
            <p class="text-xs text-[#64748B] leading-relaxed">Autoflow extracts target text, matches length, preserves HTML/CSS tags 100% intact, and generates fresh copy.</p>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-[#E2E8F0] shadow-card space-y-3 relative overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#15803D] font-black text-sm flex items-center justify-center border border-emerald-100">
                04
            </div>
            <h4 class="text-sm font-bold text-[#0F172A]">Approve & Live Deploy</h4>
            <p class="text-xs text-[#64748B] leading-relaxed">Review the generated text diff and click "Approve & Push" (or enable Automatic Mode) to trigger instant Vercel / Netlify build.</p>
        </div>
    </div>

    <!-- Detailed Step-by-Step Sections -->
    <div class="space-y-6">
        
        <!-- Step 1 Guide -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-card p-6 sm:p-8 space-y-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#0F172A] text-[#22C55E] flex items-center justify-center font-bold text-sm shrink-0">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <div class="space-y-2 flex-1">
                    <h3 class="text-base font-bold text-[#0F172A]">Step 1: Connecting your Website via GitHub</h3>
                    <p class="text-xs text-[#64748B] leading-relaxed">
                        Go to <a href="{{ route('websites.index') }}" class="text-[#15803D] font-semibold hover:underline">Websites</a> and click <strong>"Connect Website"</strong>:
                    </p>
                    <div class="p-4 rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] space-y-2">
                        <div class="font-bold text-xs text-[#0F172A] flex items-center gap-1.5">
                            <i class="fa-brands fa-github text-[#22C55E]"></i> GitHub Cloud Integration (100% Zero Server Disk Storage)
                        </div>
                        <p class="text-xs text-[#64748B] leading-relaxed">
                            Enter your repository URL (e.g. <code class="bg-white px-1.5 py-0.5 rounded text-gray-800 border">https://github.com/username/repository</code>) and target branch (<code class="bg-white px-1.5 py-0.5 rounded text-gray-800 border">main</code>). Autoflow connects directly via GitHub REST API without needing Git binaries or storing files on your host.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2 Guide -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-card p-6 sm:p-8 space-y-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#0F172A] text-[#22C55E] flex items-center justify-center font-bold text-sm shrink-0">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <div class="space-y-2 flex-1">
                    <h3 class="text-base font-bold text-[#0F172A]">Step 2: Configuring AI Providers & Models</h3>
                    <p class="text-xs text-[#64748B] leading-relaxed">
                        Navigate to <a href="{{ route('ai.models') }}" class="text-[#15803D] font-semibold hover:underline">AI Models</a> to link your AI engines. You can register up to 3 Providers and 10 Models:
                    </p>
                    <ul class="text-xs text-[#475569] space-y-2 list-disc list-inside pt-1">
                        <li><strong>Cloud AI Providers:</strong> Add your API Key for Groq (Llama 3.3 70B), OpenAI (GPT-4o), Anthropic (Claude 3.5), Google Gemini, or OpenRouter.</li>
                        <li><strong>Local Server (Ollama):</strong> If you run a local LLM, point the endpoint to <code class="bg-slate-100 px-1.5 py-0.5 rounded text-gray-800">http://127.0.0.1:11434</code> without any API key!</li>
                        <li><strong>Test Connection:</strong> Always click the <strong>"Test Connection"</strong> button to ensure your API credentials and endpoints are valid.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Step 3 Guide -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-card p-6 sm:p-8 space-y-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#0F172A] text-[#22C55E] flex items-center justify-center font-bold text-sm shrink-0">
                    <i class="fa-solid fa-gears"></i>
                </div>
                <div class="space-y-2 flex-1">
                    <h3 class="text-base font-bold text-[#0F172A]">Step 3: Running Rewrites & Reviewing Output</h3>
                    <p class="text-xs text-[#64748B] leading-relaxed">
                        When a rewrite job is triggered (either automatically via interval or manually from the dashboard):
                    </p>
                    <div class="bg-[#F0FDF4] p-4 rounded-xl border border-[#DCFCE7] space-y-2">
                        <div class="flex items-center gap-2 font-bold text-xs text-[#15803D]">
                            <i class="fa-solid fa-shield-halved"></i>
                            Layout Preservation & HTML Integrity
                        </div>
                        <p class="text-[11px] text-[#166534] leading-relaxed">
                            Autoflow's core engine safely isolates visible marketing copy while completely protecting structural tags (<code class="bg-white/80 px-1 rounded">&lt;header&gt;</code>, <code class="bg-white/80 px-1 rounded">&lt;nav&gt;</code>, class names, styles, scripts, and links).
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4 Guide -->
        <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-card p-6 sm:p-8 space-y-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#0F172A] text-[#22C55E] flex items-center justify-center font-bold text-sm shrink-0">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
                <div class="space-y-2 flex-1">
                    <h3 class="text-base font-bold text-[#0F172A]">Frequently Asked Questions</h3>
                    <div class="space-y-3 pt-2">
                        <div class="border-b border-gray-100 pb-2.5">
                            <h5 class="text-xs font-bold text-[#0F172A]">How do I change how often my site updates?</h5>
                            <p class="text-[11px] text-[#64748B] mt-0.5">Go to Websites &gt; Edit Website &gt; Set your preferred rewrite interval (e.g. every 7 days, 24 hours, etc.).</p>
                        </div>
                        <div class="border-b border-gray-100 pb-2.5">
                            <h5 class="text-xs font-bold text-[#0F172A]">Can I prevent specific brand terms from changing?</h5>
                            <p class="text-[11px] text-[#64748B] mt-0.5">Yes! When connecting or editing a website, add terms to the <strong>"Protected Terms"</strong> list (e.g. Company Name, Trademarks, Phone Numbers).</p>
                        </div>
                        <div>
                            <h5 class="text-xs font-bold text-[#0F172A]">Where can I upgrade my SaaS Plan?</h5>
                            <p class="text-[11px] text-[#64748B] mt-0.5">Visit the <a href="{{ route('subscription') }}" class="text-[#15803D] font-bold hover:underline">Subscription & Plans</a> page in your sidebar to upgrade to Pro Agency or Enterprise.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
