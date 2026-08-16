<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('reviews.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                    ← Back to Review Queue
                </a>
                <h1 class="text-2xl font-bold text-[#101828] tracking-tight mt-1">Reviewing Diff Studio: {{ $rewrite->page_path }}</h1>
                <p class="text-xs text-[#667085]">Website: <span class="font-semibold text-[#101828]">{{ $rewrite->website_name }}</span> ({{ $rewrite->domain }})</p>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                <button
                    wire:click="reject"
                    type="button"
                    class="px-4 py-2 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 font-semibold text-xs transition-colors"
                >
                    Reject Candidate
                </button>
                <button
                    wire:click="approveAndPush"
                    type="button"
                    class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition-colors"
                >
                    Approve & Push Commit →
                </button>
            </div>
        </div>
    </div>

    <!-- Main Diff Studio Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2/3: Diff Editor -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#EAECF0] shadow-xs overflow-hidden">
            <div class="p-4 border-b border-[#EAECF0] bg-[#F9FAFB] flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#101828]">Diff Inspector</h3>
                <span class="text-xs text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200 font-semibold">{{ $rewrite->ai_model }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-[#EAECF0] font-mono text-xs">
                <div class="p-4 space-y-2 bg-rose-50/20">
                    <span class="text-[11px] font-bold text-rose-800 uppercase block border-b border-rose-200/60 pb-1">Current Published Version</span>
                    <pre class="text-rose-950 whitespace-pre-wrap leading-relaxed">{{ $rewrite->original_content }}</pre>
                </div>
                <div class="p-4 space-y-2 bg-emerald-50/20">
                    <span class="text-[11px] font-bold text-emerald-800 uppercase block border-b border-emerald-200/60 pb-1">Proposed AI Revision</span>
                    <pre class="text-emerald-950 whitespace-pre-wrap leading-relaxed">{{ $rewrite->rewritten_content }}</pre>
                </div>
            </div>

            <div class="p-4 border-t border-[#EAECF0] bg-[#F9FAFB] space-y-2">
                <label class="block text-xs font-semibold text-[#344054]">Git Commit Message</label>
                <input
                    wire:model="commitMessage"
                    type="text"
                    class="w-full px-3 py-2 text-xs font-mono rounded-xl border border-[#D0D5DD] bg-white text-[#101828]"
                >
            </div>
        </div>

        <!-- Right 1/3: Compliance -->
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-5 space-y-3 text-xs">
                <h3 class="text-sm font-bold text-[#101828] border-b border-[#EAECF0] pb-2">Compliance Guardrails</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-[#667085]">Protected Terms Check:</span>
                        <span class="font-bold text-emerald-600">Passed</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#667085]">SEO Quality Score:</span>
                        <span class="font-bold text-emerald-600">98.6 / 100</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#667085]">Brand Voice Tone:</span>
                        <span class="font-medium text-[#101828]">Energetic SaaS</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
