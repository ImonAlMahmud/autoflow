<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-[#101828]">AI Providers & Model Registry</h1>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $providers->count() >= 3 ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-emerald-50 text-[#15803D] border border-emerald-200' }}">
                        Providers: {{ $providers->count() }}/3
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $models->count() >= 10 ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-emerald-50 text-[#15803D] border border-emerald-200' }}">
                        Models: {{ $models->count() }}/10
                    </span>
                </div>
            </div>
            <p class="text-xs text-[#667085] mt-1">Configure cloud AI API keys (OpenAI, Anthropic, Gemini, OpenRouter) or local servers (Ollama, LM Studio). Max 3 Providers & 10 Models allowed.</p>
        </div>
        <div class="flex items-center gap-3">
            @if(auth()->user()?->hasActiveSubscription())
                <button wire:click="openNewProviderModal" type="button" class="px-4 py-2 bg-white border border-[#CBD5E1] hover:bg-[#F8FAFC] text-[#334155] font-semibold text-xs rounded-xl shadow-xs transition-all flex items-center gap-2 {{ $providers->count() >= 3 ? 'opacity-60' : '' }}">
                    + Add AI Provider / Server
                </button>
                <button wire:click="openNewModelModal" type="button" class="px-5 py-2.5 bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:scale-105 flex items-center gap-2 {{ $models->count() >= 10 ? 'opacity-60' : '' }}">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Register New Model
                </button>
            @else
                <button @click="$dispatch('open-paywall', { feature: 'AI Provider Setup' })" type="button" class="px-4 py-2 bg-white border border-[#CBD5E1] hover:bg-[#F8FAFC] text-[#334155] font-semibold text-xs rounded-xl shadow-xs transition-all flex items-center gap-2">
                    <i class="fa-solid fa-lock text-xs text-amber-500"></i> + Add AI Provider / Server
                </button>
                <button @click="$dispatch('open-paywall', { feature: 'AI Model Registry' })" type="button" class="px-5 py-2.5 bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:scale-105 flex items-center gap-2">
                    <i class="fa-solid fa-lock text-xs"></i> Register New Model
                </button>
            @endif
        </div>
    </div>

    <!-- Active Providers List Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($providers as $prov)
            <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-card p-5 space-y-4 flex flex-col justify-between">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#101828] uppercase tracking-wider">{{ $prov->name }}</span>
                        <div class="flex items-center gap-1.5">
                            <button wire:click="editProvider({{ $prov->id }})" type="button" class="p-1 text-[#667085] hover:text-[#15803D] rounded hover:bg-gray-100 transition-colors" title="Edit Provider">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <button wire:click="deleteProvider({{ $prov->id }})" type="button" class="p-1 text-[#667085] hover:text-rose-600 rounded hover:bg-gray-100 transition-colors" title="Remove Provider">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ strtoupper(is_object($prov->driver) ? $prov->driver->value : $prov->driver) }}
                            </span>
                        </div>
                    </div>
                    <p class="text-xs text-[#667085] font-mono truncate" title="{{ $prov->endpoint }}">Endpoint: {{ $prov->endpoint }}</p>
                    @if($prov->api_key)
                        <p class="text-xs text-[#98A2B3]">API Key: ••••••••••••••••</p>
                    @else
                        <p class="text-xs text-amber-600 font-medium">Local / No API Key required</p>
                    @endif
                </div>

                <!-- Inline Connection Status Feedback -->
                @if(isset($testResults[$prov->id]))
                    <div class="p-2.5 rounded-xl text-xs flex items-center gap-2 {{ $testResults[$prov->id]['status'] === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' }}">
                        <i class="fa-solid {{ $testResults[$prov->id]['status'] === 'success' ? 'fa-circle-check text-emerald-600' : 'fa-circle-xmark text-rose-600' }}"></i>
                        <span class="font-medium text-[11px]">{{ $testResults[$prov->id]['message'] }}</span>
                    </div>
                @endif

                <div class="pt-3 border-t border-[#EAECF0] flex items-center justify-between">
                    <span class="text-xs text-[#667085]">{{ $prov->models->count() }} Models configured</span>
                    <button
                        wire:click="testConnection({{ $prov->id }})"
                        wire:loading.attr="disabled"
                        wire:target="testConnection({{ $prov->id }})"
                        type="button"
                        class="px-3 py-1.5 bg-[#F9FAFB] hover:bg-white border border-[#D0D5DD] text-xs font-semibold text-[#344054] rounded-lg transition-all shadow-xs inline-flex items-center gap-1.5 disabled:opacity-75 disabled:cursor-wait"
                    >
                        <span wire:loading.remove wire:target="testConnection({{ $prov->id }})" class="inline-flex items-center gap-1">
                            <i class="fa-solid fa-bolt text-xs text-[#15803D]"></i>
                            Test Endpoint
                        </span>
                        <span wire:loading wire:target="testConnection({{ $prov->id }})" class="inline-flex items-center gap-1">
                            <i class="fa-solid fa-spinner fa-spin text-xs text-[#15803D]"></i>
                            Testing...
                        </span>
                    </button>
                </div>
            </div>
        @empty
            <div class="md:col-span-3 p-8 bg-white rounded-2xl border border-[#EAECF0] text-center space-y-3">
                <p class="text-xs text-[#667085]">No AI Providers configured yet. Add local Ollama or cloud API credentials.</p>
            </div>
        @endforelse
    </div>

    <!-- Registered Models Table -->
    <div class="bg-white rounded-2xl border border-[#EAECF0] shadow-card overflow-hidden">
        <div class="p-5 border-b border-[#EAECF0]">
            <h3 class="text-sm font-bold text-[#101828]">Available Model Catalog</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-[#344054]">
                <thead class="bg-[#F9FAFB] text-[11px] font-semibold uppercase text-[#667085] border-b border-[#EAECF0]">
                    <tr>
                        <th class="px-5 py-3">Model Name & ID</th>
                        <th class="px-5 py-3">Provider</th>
                        <th class="px-5 py-3">Context Window</th>
                        <th class="px-5 py-3">Temperature</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EAECF0]">
                    @forelse($models as $m)
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="px-5 py-3.5 font-medium text-[#101828]">
                                <div class="font-semibold">{{ $m->name }}</div>
                                <div class="text-[11px] font-mono text-[#667085]">{{ $m->model_id }}</div>
                            </td>
                            <td class="px-5 py-3.5">{{ $m->provider->name ?? 'Custom' }}</td>
                            <td class="px-5 py-3.5 font-mono">{{ number_format($m->context_length ?? 8192) }} tokens</td>
                            <td class="px-5 py-3.5">{{ $m->temperature }}</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Active
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-2">
                                <button wire:click="editModel({{ $m->id }})" type="button" class="text-[#15803D] hover:text-[#166534] text-xs font-bold transition-colors inline-flex items-center gap-1">
                                    <i class="fa-solid fa-pen text-[10px]"></i> Edit
                                </button>
                                <button wire:click="deleteModel({{ $m->id }})" wire:confirm="Are you sure you want to remove this model?" type="button" class="text-rose-600 hover:text-rose-800 text-xs font-bold transition-colors inline-flex items-center gap-1">
                                    <i class="fa-solid fa-trash text-[10px]"></i> Remove
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-xs text-[#98A2B3]">No AI models registered yet. Register a local Ollama model or cloud API model above.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal 1: Add AI Provider / Endpoint -->
    @if($showProviderModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4 animate-in fade-in duration-150">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-base font-bold text-[#0F172A]">
                        {{ $editingProviderId ? 'Edit AI Provider' : 'Add AI Provider / Local Server' }}
                    </h3>
                    <button wire:click="$set('showProviderModal', false)" type="button" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-[#344054] mb-1">Provider Type / Driver</label>
                        <select wire:model.live="providerDriver" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white">
                            <option value="ollama">Ollama (Local Server)</option>
                            <option value="groq">Groq Cloud API (Ultra Fast)</option>
                            <option value="openai_compatible">OpenAI / OpenAI Compatible API</option>
                            <option value="anthropic">Anthropic Claude API</option>
                            <option value="gemini">Google Gemini API</option>
                            <option value="openrouter">OpenRouter API</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#344054] mb-1">Display Name</label>
                        <input type="text" wire:model="providerName" placeholder="e.g. My Local Ollama or Production OpenAI" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#344054] mb-1">API Endpoint / Server URL</label>
                        <input type="text" wire:model="providerEndpoint" placeholder="http://127.0.0.1:11434" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#344054] mb-1">API Key (Optional for Local Server)</label>
                        <input type="password" wire:model="providerApiKey" placeholder="sk-..." class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD]">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <button wire:click="$set('showProviderModal', false)" type="button" class="px-4 py-2 text-xs font-semibold text-[#667085] hover:bg-[#F9FAFB] rounded-xl">Cancel</button>
                    <button wire:click="saveProvider" type="button" class="px-5 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs shadow-sm transition-all">Save Provider</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 2: Register / Edit Model -->
    @if($showModelModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4 animate-in fade-in duration-150">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-base font-bold text-[#0F172A]">
                        {{ $editingModelId ? 'Edit AI Model Configuration' : 'Register New AI Model' }}
                    </h3>
                    <button wire:click="$set('showModelModal', false)" type="button" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-[#344054] mb-1">Select Provider</label>
                        <select wire:model="selectedProviderId" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] bg-white">
                            @foreach($providers as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->driver }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#344054] mb-1">Model Display Name</label>
                        <input type="text" wire:model="modelName" placeholder="e.g. Groq Llama 3.3 70B or GPT-4o" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD] text-[#0F172A]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#344054] mb-1">Model Identifier / Slug *</label>
                        <input type="text" wire:model="modelIdentifier" placeholder="e.g. llama-3.3-70b-versatile or gpt-4o" class="w-full px-3 py-2 text-xs font-mono rounded-xl border border-[#D0D5DD] text-[#0F172A]">
                        <p class="text-[10px] text-[#64748B] mt-0.5">Groq Recommended: <code class="bg-gray-100 px-1 rounded text-emerald-700">llama-3.3-70b-versatile</code></p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">Temperature</label>
                            <input type="number" step="0.05" wire:model="temperature" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD]">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[#344054] mb-1">Context Length</label>
                            <input type="number" wire:model="contextLength" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD]">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <button wire:click="$set('showModelModal', false)" type="button" class="px-4 py-2 text-xs font-semibold text-[#667085] hover:bg-[#F9FAFB] rounded-xl">Cancel</button>
                    <button wire:click="saveModel" type="button" class="px-5 py-2.5 rounded-xl bg-[#22C55E] hover:bg-[#16A34A] text-white font-bold text-xs shadow-sm transition-all hover:scale-105">
                        {{ $editingModelId ? 'Update Model' : 'Register Model' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
