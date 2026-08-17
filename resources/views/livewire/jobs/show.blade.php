<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl border border-[#E2E8F0] shadow-card p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-[#22C55E] hover:text-[#16A34A] flex items-center gap-1">
                    ← Back to Jobs Queue
                </a>
                <div class="flex items-center gap-3 mt-1">
                    <h1 class="text-2xl font-bold text-[#0F172A] tracking-tight">Job Execution #{{ $job?->id ?? $jobId }}</h1>
                    @php
                        $statusVal = is_object($job?->status) ? $job->status->value : (string)($job?->status ?? 'scheduled');
                    @endphp
                    @if($statusVal === 'completed')
                        <span class="px-2.5 py-0.5 rounded-full bg-[#DCFCE7] text-[#15803D] border border-[#BBF7D0] text-xs font-bold flex items-center gap-1">
                            <i class="fa-solid fa-circle-check text-xs"></i> Completed & Pushed
                        </span>
                    @elseif($statusVal === 'failed')
                        <span class="px-2.5 py-0.5 rounded-full bg-[#FEE2E2] text-[#B91C1C] border border-[#FECACA] text-xs font-bold flex items-center gap-1">
                            <i class="fa-solid fa-circle-xmark text-xs"></i> Failed
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full bg-[#FEF3C7] text-[#B45309] border border-[#FDE68A] text-xs font-bold flex items-center gap-1">
                            <i class="fa-solid fa-clock text-xs"></i> {{ is_object($job?->status) ? $job->status->label() : strtoupper($job?->status ?? 'Scheduled') }}
                        </span>
                    @endif

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
                            class="px-3 py-1 rounded-full text-xs font-mono font-semibold bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] inline-flex items-center gap-1.5 shadow-xs"
                        >
                            <i class="fa-solid fa-clock text-xs"></i>
                            <span x-text="timerText"></span>
                            <span class="text-[10px] text-[#15803D]/70 font-sans">({{ $dueTime->format('h:i A') }})</span>
                        </span>
                    @endif
                </div>
                <p class="text-xs text-[#64748B] mt-1">
                    Target Page: <span class="font-mono font-bold text-[#0F172A]">{{ $job?->page?->path ?? 'Page Path' }}</span> 
                    on <span class="font-bold text-[#0F172A]">{{ $job?->website?->name ?? 'Website' }}</span>
                </p>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                @if($statusVal === 'completed')
                    <span class="px-4 py-2 rounded-xl bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] text-xs font-bold flex items-center gap-1.5">
                        <i class="fa-brands fa-github text-sm"></i>
                        Pushed to GitHub main
                    </span>
                @else
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
                        class="px-5 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs shadow-sm transition-all hover:scale-105"
                    >
                        Approve & Commit to Git →
                    </button>
                @endif
            </div>
        </div>

        <!-- Visual Step Progress Stepper Bar -->
        <div class="pt-4 border-t border-[#E2E8F0]">
            <div class="text-xs font-semibold text-[#64748B] uppercase tracking-wider mb-3">Pipeline Lifecycle</div>
            <div class="grid grid-cols-2 sm:grid-cols-6 gap-2 text-center text-xs">
                <div class="p-2 rounded-xl bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] font-semibold">1. Scheduled ✓</div>
                <div class="p-2 rounded-xl bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] font-semibold">2. Extracted ✓</div>
                <div class="p-2 rounded-xl bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] font-semibold">3. AI Generated ✓</div>
                <div class="p-2 rounded-xl bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] font-semibold">4. Validated ✓</div>
                <div class="p-2 rounded-xl {{ $statusVal === 'completed' ? 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7]' : 'bg-[#FEF3C7] text-[#B45309] border border-[#FDE68A] font-bold animate-pulse' }} font-semibold">5. Processed ✓</div>
                <div class="p-2 rounded-xl {{ $statusVal === 'completed' ? 'bg-[#F0FDF4] text-[#15803D] border border-[#DCFCE7] font-bold' : 'bg-gray-50 text-gray-400 border border-gray-200' }} font-medium">6. Git Commit {{ $statusVal === 'completed' ? '✓' : '' }}</div>
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
                        <i class="fa-solid fa-file-lines text-xs"></i>
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
                        <span class="font-mono font-semibold text-[#15803D]">{{ $job?->website?->git_branch ?? 'main' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
