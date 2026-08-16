<div class="space-y-6">
    <!-- Top Bar Header Card -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('pages.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                    ← Back to All Pages
                </a>
                <div class="flex items-center gap-3 mt-1">
                    <h1 class="text-2xl font-bold font-mono text-[#101828] tracking-tight">{{ $page->path }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 text-xs font-semibold">
                        {{ $page->friendly_name ?? 'Static Document' }}
                    </span>
                </div>
                <p class="text-xs text-[#667085] mt-1">Website: <span class="font-medium text-[#101828]">{{ $page->website_name ?? 'TechCorp Documentation' }}</span> ({{ $page->domain ?? 'techcorp.io' }})</p>
            </div>

            <!-- Header Actions -->
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <button
                    wire:click="triggerPageRewrite"
                    type="button"
                    class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-colors flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    Run AI Rewrite Now
                </button>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-[#EAECF0] text-xs">
            <div>
                <span class="text-[11px] text-[#667085] uppercase font-semibold">Word Count</span>
                <p class="text-sm font-bold text-[#101828] mt-0.5">{{ number_format($page->word_count ?? 1240) }} words</p>
            </div>
            <div>
                <span class="text-[11px] text-[#667085] uppercase font-semibold">Target Model</span>
                <p class="text-sm font-bold text-purple-700 mt-0.5">{{ $page->ai_model ?? 'GPT-4o' }}</p>
            </div>
            <div>
                <span class="text-[11px] text-[#667085] uppercase font-semibold">Last Refreshed</span>
                <p class="text-sm font-bold text-[#101828] mt-0.5">3 days ago</p>
            </div>
            <div>
                <span class="text-[11px] text-[#667085] uppercase font-semibold">Next Scheduled Run</span>
                <p class="text-sm font-bold text-blue-600 mt-0.5">In 11 days</p>
            </div>
        </div>
    </div>

    <!-- Main Content & Diff Viewer Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT 2/3 COLUMN: CONTENT DIFF VIEWER -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs overflow-hidden">
                <!-- Diff Card Bar -->
                <div class="p-4 border-b border-[#EAECF0] flex items-center justify-between">
                    <h3 class="text-sm font-bold text-[#101828] flex items-center gap-2">
                        Content Diff Comparison
                        <span class="px-2 py-0.5 rounded text-[10px] bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">Latest Version</span>
                    </h3>

                    <!-- Toggle Split / Unified -->
                    <div class="flex items-center gap-1 bg-[#F2F4F7] p-1 rounded-lg border border-[#EAECF0] text-xs">
                        <button
                            wire:click="$set('diffMode', 'split')"
                            type="button"
                            class="px-2.5 py-1 rounded-md font-semibold transition-all {{ $diffMode === 'split' ? 'bg-white text-indigo-600 shadow-xs' : 'text-[#667085]' }}"
                        >
                            Split View
                        </button>
                        <button
                            wire:click="$set('diffMode', 'unified')"
                            type="button"
                            class="px-2.5 py-1 rounded-md font-semibold transition-all {{ $diffMode === 'unified' ? 'bg-white text-indigo-600 shadow-xs' : 'text-[#667085]' }}"
                        >
                            Unified View
                        </button>
                    </div>
                </div>

                <!-- Diff Body -->
                @if($diffMode === 'split')
                    <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-[#EAECF0] bg-white font-mono text-xs">
                        <!-- Original Content Left Pane -->
                        <div class="p-4 space-y-2 bg-rose-50/20">
                            <span class="text-[11px] font-bold text-rose-800 uppercase tracking-wider block border-b border-rose-200/60 pb-2">Original Content</span>
                            <div class="text-rose-950 whitespace-pre-wrap leading-relaxed">
- # Cloud Platform Overview
- Our platform provides cloud infrastructure for modern developers.
- It features scalable databases, containerized functions, and global CDN caching.
                            </div>
                        </div>

                        <!-- Rewritten Content Right Pane -->
                        <div class="p-4 space-y-2 bg-emerald-50/20">
                            <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider block border-b border-emerald-200/60 pb-2">AI Generated Refresh</span>
                            <div class="text-emerald-950 whitespace-pre-wrap leading-relaxed">
+ # Cloud Infrastructure Platform
+ Empower engineering teams with ultra-fast cloud infrastructure.
+ Instantly deploy serverless functions, multi-region database clusters, and lightning-fast edge CDN distribution.
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-4 font-mono text-xs bg-slate-900 text-slate-200 space-y-1 overflow-x-auto">
                        <div class="text-slate-400">@@ -1,4 +1,4 @@</div>
                        <div class="text-rose-400 bg-rose-950/40 px-2 py-0.5 rounded">- # Cloud Platform Overview</div>
                        <div class="text-emerald-400 bg-emerald-950/40 px-2 py-0.5 rounded">+ # Cloud Infrastructure Platform</div>
                        <div class="text-rose-400 bg-rose-950/40 px-2 py-0.5 rounded">- Our platform provides cloud infrastructure for modern developers.</div>
                        <div class="text-emerald-400 bg-emerald-950/40 px-2 py-0.5 rounded">+ Empower engineering teams with ultra-fast cloud infrastructure.</div>
                        <div class="text-rose-400 bg-rose-950/40 px-2 py-0.5 rounded">- It features scalable databases, containerized functions, and global CDN caching.</div>
                        <div class="text-emerald-400 bg-emerald-950/40 px-2 py-0.5 rounded">+ Instantly deploy serverless functions, multi-region database clusters, and lightning-fast edge CDN distribution.</div>
                    </div>
                @endif

                <div class="p-3 bg-[#F9FAFB] border-t border-[#EAECF0] text-xs text-[#667085] flex items-center justify-between">
                    <span>Validation Score: <strong class="text-emerald-600">99.4/100 (Passed)</strong></span>
                    <span>Diff stats: <span class="text-emerald-600 font-semibold">+3 lines</span>, <span class="text-rose-600 font-semibold">-3 lines</span></span>
                </div>
            </div>
        </div>

        <!-- RIGHT 1/3 COLUMN: PROMPT OVERRIDES & SETTINGS -->
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-5 space-y-4">
                <h3 class="text-sm font-bold text-[#101828]">Page Custom Prompt</h3>
                <p class="text-xs text-[#667085]">Override global prompt templates with specific instructions for this page</p>

                <textarea
                    wire:model="overridePrompt"
                    rows="6"
                    class="w-full p-3 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-sans"
                ></textarea>

                <button
                    wire:click="savePromptOverride"
                    type="button"
                    class="w-full py-2 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition-colors shadow-xs"
                >
                    Save Custom Page Prompt
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-5 space-y-3 text-xs">
                <h3 class="text-sm font-bold text-[#101828]">SEO & Content Health</h3>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[#667085]">Readability Grade:</span>
                        <span class="font-bold text-[#101828]">Grade 8 (Optimal)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[#667085]">Protected Terms Check:</span>
                        <span class="font-bold text-emerald-600">100% Compliant</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[#667085]">HTML Syntax Parsed:</span>
                        <span class="font-bold text-emerald-600">Valid</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
