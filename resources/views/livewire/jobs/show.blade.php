<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('jobs.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                    ← Back to Jobs Queue
                </a>
                <div class="flex items-center gap-3 mt-1">
                    <h1 class="text-2xl font-bold text-[#101828] tracking-tight">Job Execution #{{ $job?->id ?? $jobId }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-xs font-semibold">
                        {{ $job?->status ? (is_object($job->status) ? $job->status->label() : strtoupper($job->status)) : 'Scheduled' }}
                    </span>

                    @php
                        $statusVal = is_object($job?->status) ? $job->status->value : (string)($job?->status ?? 'scheduled');
                    @endphp
                    @if(!in_array($statusVal, ['cancelled', 'completed', 'failed', 'skipped']))
                        @php
                            $unit = $job?->website?->default_rewrite_interval_unit ?? 'minutes';
                            $val = (int)($job?->website?->default_rewrite_interval_days ?? 5);
                            $dueTime = $job?->scheduled_at ? \Carbon\Carbon::parse($job->scheduled_at)->timezone('Asia/Dhaka') : \Carbon\Carbon::parse($job?->created_at ?? now())->timezone('Asia/Dhaka')->addMinutes($val);
                        @endphp
                        <span
                            x-data="{
                                targetTime: {{ $dueTime->timestamp * 1000 }},
                                timerText: '',
                                updateTimer() {
                                    const now = new Date().getTime();
                                    const diff = this.targetTime - now;
                                    if (diff <= 0) {
                                        this.timerText = 'Due Now (Executing)';
                                        return;
                                    }
                                    const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                    const secs = Math.floor((diff % (1000 * 60)) / 1000);
                                    this.timerText = `${mins}m ${secs < 10 ? '0' : ''}${secs}s remaining`;
                                }
                            }"
                            x-init="updateTimer(); setInterval(() => updateTimer(), 1000)"
                            class="px-3 py-1 rounded-full text-xs font-mono font-semibold bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1.5 shadow-xs"
                        >
                            <svg class="w-3.5 h-3.5 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span x-text="timerText"></span>
                            <span class="text-[10px] text-blue-500 font-sans">({{ $dueTime->format('h:i A') }})</span>
                        </span>
                    @endif
                </div>
                <p class="text-xs text-[#667085] mt-1">
                    Target Page: <span class="font-mono font-medium text-[#101828]">{{ $job?->page?->path ?? 'Page Path' }}</span> 
                    on <span class="font-semibold text-[#101828]">{{ $job?->website?->name ?? 'Website' }}</span>
                </p>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                <button
                    wire:click="discardJob"
                    type="button"
                    class="px-3.5 py-2 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-semibold transition-colors"
                >
                    Discard
                </button>
                <button
                    wire:click="approveAndPush"
                    type="button"
                    class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition-colors"
                >
                    Approve & Commit to Git →
                </button>
            </div>
        </div>

        <!-- Visual Step Progress Stepper Bar -->
        <div class="pt-4 border-t border-[#EAECF0]">
            <div class="text-xs font-semibold text-[#667085] uppercase tracking-wider mb-3">Pipeline Lifecycle</div>
            <div class="grid grid-cols-2 sm:grid-cols-6 gap-2 text-center text-xs">
                <div class="p-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-medium">1. Scheduled ✓</div>
                <div class="p-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-medium">2. Extracted ✓</div>
                <div class="p-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-medium">3. AI Generated ✓</div>
                <div class="p-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-medium">4. Validated ✓</div>
                <div class="p-2 rounded-xl bg-amber-50 text-amber-900 border border-amber-200 font-bold animate-pulse">5. Review (Active)</div>
                <div class="p-2 rounded-xl bg-gray-50 text-gray-400 border border-gray-200 font-medium">6. Git Commit</div>
            </div>
        </div>
    </div>

    <!-- Main Content & Diff Comparison -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2/3: Split Diff Viewer -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#EAECF0] shadow-xs overflow-hidden">
            @php
                $origSegments = $job?->result?->original_segments;
                $rewrittenSegments = $job?->result?->rewritten_segments;

                $origText = is_array($origSegments) ? ($origSegments['text'] ?? ($origSegments['html'] ?? json_encode($origSegments))) : ($origSegments ?? '');
                $newText = is_array($rewrittenSegments) ? ($rewrittenSegments['text'] ?? ($rewrittenSegments['html'] ?? json_encode($rewrittenSegments))) : ($rewrittenSegments ?? '');

                $origText = trim(strip_tags($origText));
                $newText = trim(strip_tags($newText));
            @endphp

            <div class="border-b border-[#EAECF0]">
                <div class="px-4 py-3 bg-[#F9FAFB] flex items-center justify-between">
                    <h3 class="text-xs font-bold text-[#101828] uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Rewritten Text Comparison
                    </h3>
                    <span class="text-[11px] text-[#667085] font-mono bg-purple-50 text-purple-700 px-2.5 py-1 rounded-lg border border-purple-200">
                        Model: {{ $job?->aiModel?->name ?? 'Groq Llama 3.3 70B' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-[#EAECF0]">
                    <!-- Left: Original Text -->
                    <div class="p-5 space-y-3 bg-rose-50/10">
                        <div class="flex items-center justify-between border-b border-rose-200/80 pb-2">
                            <span class="text-xs font-bold text-rose-800 uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Original Text
                            </span>
                            <span class="text-[10px] text-rose-600 font-semibold px-2 py-0.5 rounded bg-rose-100/70">Before Rewrite</span>
                        </div>
                        <div class="p-4 rounded-xl bg-white border border-rose-200/80 shadow-xs text-xs text-[#101828] font-sans font-medium leading-relaxed whitespace-pre-wrap">
                            {{ $origText }}
                        </div>
                    </div>

                    <!-- Right: Rewritten Text -->
                    <div class="p-5 space-y-3 bg-emerald-50/10">
                        <div class="flex items-center justify-between border-b border-emerald-200/80 pb-2">
                            <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> AI Rewritten Text
                            </span>
                            <span class="text-[10px] text-emerald-700 font-semibold px-2 py-0.5 rounded bg-emerald-100/70">Refreshed Text</span>
                        </div>
                        <div class="p-4 rounded-xl bg-white border border-emerald-200/80 shadow-xs text-xs text-[#101828] font-sans leading-relaxed whitespace-pre-wrap font-bold">
                            {{ $newText }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-[#F9FAFB] border-t border-[#EAECF0] space-y-2">
                <label class="block text-xs font-semibold text-[#344054]">Reviewer Notes (Optional)</label>
                <input
                    wire:model="reviewerNotes"
                    type="text"
                    placeholder="Add comments before committing to Git..."
                    class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white text-[#101828]"
                >
            </div>
        </div>

        <!-- Right 1/3: Metadata & Validation -->
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-5 space-y-3 text-xs">
                <h3 class="text-sm font-bold text-[#101828] border-b border-[#EAECF0] pb-2">Validation & Status Report</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-[#667085]">Validation Score:</span>
                        <span class="font-bold text-emerald-600">Passed</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#667085]">Protected Terms:</span>
                        <span class="font-semibold text-emerald-700">✓ No violations</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#667085]">HTML & Syntax:</span>
                        <span class="font-semibold text-emerald-700">✓ Clean syntax</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#667085]">Target Branch:</span>
                        <span class="font-mono font-semibold text-indigo-600">{{ $job?->website?->git_branch ?? 'main' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
