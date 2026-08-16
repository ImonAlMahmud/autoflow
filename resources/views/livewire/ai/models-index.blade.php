<div class="space-y-6 max-w-6xl mx-auto" x-data="{ showProviderModal: false, showModelModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#101828]">AI Providers & Model Registry</h1>
            <p class="text-xs text-[#667085] mt-1">Configure cloud AI API keys (OpenAI, Anthropic, Gemini, OpenRouter) or local servers (Ollama, LM Studio, vLLM).</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="openNewProviderModal" @click="showProviderModal = true" type="button" class="px-4 py-2 bg-white border border-[#D0D5DD] hover:bg-[#F9FAFB] text-[#344054] font-semibold text-xs rounded-xl shadow-xs transition-all flex items-center gap-2">
                + Add AI Provider / Server
            </button>
            <button @click="showModelModal = true" type="button" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-all flex items-center gap-2">
                + Register New Model
            </button>
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
                            <button wire:click="editProvider({{ $prov->id }})" @click="showProviderModal = true" type="button" class="p-1 text-[#667085] hover:text-indigo-600 rounded hover:bg-gray-100 transition-colors" title="Edit Provider">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </button>
                            <button wire:click="deleteProvider({{ $prov->id }})" type="button" class="p-1 text-[#667085] hover:text-rose-600 rounded hover:bg-gray-100 transition-colors" title="Remove Provider">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
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

                <div class="pt-3 border-t border-[#EAECF0] flex items-center justify-between">
                    <span class="text-xs text-[#667085]">{{ $prov->models->count() }} Models configured</span>
                    <button wire:click.prevent="testConnection({{ $prov->id }})" type="button" class="px-3 py-1.5 bg-[#F9FAFB] hover:bg-white border border-[#D0D5DD] text-xs font-semibold text-[#344054] rounded-lg transition-colors">
                        Test Endpoint
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
                            <td class="px-5 py-3.5 text-right">
                                <button wire:click="deleteModel({{ $m->id }})" type="button" class="text-rose-600 hover:text-rose-800 text-xs font-semibold">Remove</button>
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
    <div x-show="showProviderModal" @close-modals.window="showProviderModal = false" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4" @click.self="showProviderModal = false">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl" @click.away="showProviderModal = false">
            <h3 class="text-base font-bold text-[#101828]">Add AI Provider / Local Server</h3>
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

            <div class="flex items-center justify-end gap-2 pt-2">
                <button @click="showProviderModal = false" type="button" class="px-4 py-2 text-xs font-semibold text-[#667085] hover:bg-[#F9FAFB] rounded-xl">Cancel</button>
                <button wire:click="saveProvider" type="button" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl">Save Provider</button>
            </div>
        </div>
    </div>

    <!-- Modal 2: Register New Model -->
    <div x-show="showModelModal" @close-modals.window="showModelModal = false" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4" @click.self="showModelModal = false">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl" @click.away="showModelModal = false">
            <h3 class="text-base font-bold text-[#101828]">Register AI Model</h3>
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
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Model Name</label>
                    <input type="text" wire:model="modelName" placeholder="e.g. Qwen 2.5 7B or GPT-4o" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#344054] mb-1">Model Identifier / ID</label>
                    <input type="text" wire:model="modelIdentifier" placeholder="e.g. qwen2.5:7b, llama3.1:8b, gpt-4o" class="w-full px-3 py-2 text-xs rounded-xl border border-[#D0D5DD]">
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

            <div class="flex items-center justify-end gap-2 pt-2">
                <button @click="showModelModal = false" type="button" class="px-4 py-2 text-xs font-semibold text-[#667085] hover:bg-[#F9FAFB] rounded-xl">Cancel</button>
                <button wire:click="saveModel" type="button" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl">Register Model</button>
            </div>
        </div>
    </div>
</div>
