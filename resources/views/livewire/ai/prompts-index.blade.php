<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#101828] tracking-tight">Prompt Engineering Studio</h1>
            <p class="text-xs text-[#667085] mt-1">Manage system prompts, user prompt templates, variable tags, and version history</p>
        </div>

        <button
            wire:click="saveVersion"
            type="button"
            class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-colors self-start sm:self-auto"
        >
            Publish New Prompt Version
        </button>
    </div>

    <!-- Master-Detail 2 Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT 1/3: TEMPLATES LIST -->
        <div class="space-y-3">
            <h3 class="text-sm font-bold text-[#101828]">Prompt Templates</h3>
            @foreach($templates as $tmpl)
                <div
                    wire:click="selectTemplate({{ $tmpl->id }})"
                    class="p-4 rounded-2xl border transition-all cursor-pointer {{ $selectedTemplateId === $tmpl->id ? 'bg-white border-indigo-600 shadow-md ring-2 ring-indigo-500/10' : 'bg-white border-[#EAECF0] hover:border-indigo-300 shadow-xs' }}"
                >
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-[#101828]">{{ $tmpl->name }}</h4>
                        <span class="px-2 py-0.5 text-[10px] font-mono rounded bg-purple-50 text-purple-700 font-semibold border border-purple-200">{{ $tmpl->version }}</span>
                    </div>
                    <p class="text-[11px] text-[#667085] mt-1 line-clamp-2">{{ $tmpl->description }}</p>
                </div>
            @endforeach
        </div>

        <!-- RIGHT 2/3: PROMPT EDITOR & PLAYGROUND -->
        <div class="lg:col-span-2 space-y-5">
            <!-- Editor Card -->
            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-[#EAECF0] pb-3">
                    <h3 class="text-sm font-bold text-[#101828]">System & User Prompt Configuration</h3>
                    <span class="text-xs text-[#667085]">Available Variables: <code class="bg-gray-100 px-1 rounded text-indigo-600 font-mono">\{\{ content \}\}</code>, <code class="bg-gray-100 px-1 rounded text-indigo-600 font-mono">\{\{ protected_terms \}\}</code></span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">System Instruction Persona</label>
                    <textarea
                        wire:model="systemPrompt"
                        rows="3"
                        class="w-full p-3 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-mono"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">User Prompt Structure</label>
                    <textarea
                        wire:model="userPrompt"
                        rows="6"
                        class="w-full p-3 text-xs rounded-xl border border-[#D0D5DD] bg-[#F9FAFB] focus:bg-white text-[#101828] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-mono"
                    ></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button
                        wire:click="testRun"
                        type="button"
                        class="px-4 py-2 rounded-xl border border-[#D0D5DD] bg-white hover:bg-[#F9FAFB] text-xs font-semibold text-[#344054] transition-colors shadow-xs flex items-center gap-2"
                    >
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Test Prompt Playground
                    </button>
                </div>
            </div>

            <!-- Playground Test Preview Card -->
            @if($playgroundOutput)
                <div class="bg-white rounded-2xl border border-indigo-200 shadow-xs p-6 space-y-3">
                    <h4 class="text-xs font-bold text-indigo-900 uppercase tracking-wider">Playground Generated Preview Output</h4>
                    <div class="p-4 rounded-xl bg-indigo-50/50 border border-indigo-100 font-mono text-xs text-[#101828] whitespace-pre-wrap">
                        {{ $playgroundOutput }}
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
