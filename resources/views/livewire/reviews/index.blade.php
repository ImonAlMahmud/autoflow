<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#101828] tracking-tight">Pending Human Approvals Queue</h1>
            <p class="text-xs text-[#667085] mt-1">Review AI-generated content rewrites before committing to live Git repositories</p>
        </div>

        @if(count($selectedReviews) > 0)
            <button
                wire:click="approveBatch"
                type="button"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition-colors self-start sm:self-auto"
            >
                Approve & Commit {{ count($selectedReviews) }} Selected
            </button>
        @endif
    </div>

    <!-- Reviews Grid -->
    <div class="space-y-4">
        @foreach($reviews as $rev)
            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs hover:shadow-card transition-all p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#EAECF0] pb-3">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model.live="selectedReviews" value="{{ $rev->id }}" class="rounded border-[#D0D5DD] text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <a href="{{ route('reviews.show', $rev->id) }}" class="font-bold text-base text-[#101828] hover:text-indigo-600 font-mono">
                                {{ $rev->page_path }}
                            </a>
                            <p class="text-xs text-[#667085] mt-0.5">Website: <span class="font-medium text-[#101828]">{{ $rev->website_name }}</span> ({{ $rev->domain }})</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 text-[11px] rounded-full bg-purple-50 text-purple-700 font-semibold border border-purple-200">
                            {{ $rev->ai_model }}
                        </span>
                        <span class="px-2.5 py-1 text-[11px] rounded-full bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
                            Score: {{ $rev->validation_score }}%
                        </span>
                    </div>
                </div>

                <p class="text-xs text-[#475467] leading-relaxed">
                    <strong class="text-[#101828]">Summary of Changes:</strong> {{ $rev->diff_summary }}
                </p>

                <div class="flex items-center justify-between pt-2 text-xs">
                    <span class="text-[#98A2B3]">{{ $rev->created_at->diffForHumans() }}</span>

                    <div class="flex items-center gap-2">
                        <button
                            wire:click="rejectReview({{ $rev->id }})"
                            type="button"
                            class="px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 font-semibold hover:bg-rose-100 transition-colors"
                        >
                            Reject
                        </button>
                        <a
                            href="{{ route('reviews.show', $rev->id) }}"
                            class="px-3.5 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold transition-colors"
                        >
                            Inspect Diff Studio →
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
