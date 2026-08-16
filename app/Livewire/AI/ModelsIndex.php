<?php

namespace App\Livewire\AI;

use App\Enums\AIModelStatus;
use App\Models\AiModel;
use App\Models\AiProvider;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ModelsIndex extends Component
{
    // Provider Form Properties
    public ?int $editingProviderId = null;
    public string $providerName = '';
    public string $providerDriver = 'ollama'; // ollama, groq, openai_compatible, anthropic, gemini, openrouter
    public string $providerEndpoint = 'http://127.0.0.1:11434';
    public string $providerApiKey = '';

    // Add Custom Model Form
    public ?int $selectedProviderId = null;
    public string $modelName = '';
    public string $modelIdentifier = '';
    public float $temperature = 0.70;
    public int $contextLength = 8192;

    public bool $showProviderModal = false;
    public bool $showModelModal = false;

    public function mount()
    {
        $firstProvider = AiProvider::first();
        if ($firstProvider) {
            $this->selectedProviderId = $firstProvider->id;
        }
    }

    public function openNewProviderModal()
    {
        $this->editingProviderId = null;
        $this->providerName = '';
        $this->providerDriver = 'ollama';
        $this->providerEndpoint = 'http://127.0.0.1:11434';
        $this->providerApiKey = '';
        $this->showProviderModal = true;
    }

    public function editProvider($id)
    {
        $provider = AiProvider::find($id);
        if (!$provider) return;

        $this->editingProviderId = $provider->id;
        $this->providerName = $provider->name;
        $this->providerDriver = is_object($provider->driver) ? $provider->driver->value : (string)$provider->driver;
        $this->providerEndpoint = $provider->endpoint;
        $this->providerApiKey = $provider->api_key ?? '';
        $this->showProviderModal = true;
    }

    public function updatedProviderDriver($val)
    {
        if ($val === 'ollama') {
            $this->providerEndpoint = 'http://127.0.0.1:11434';
        } elseif ($val === 'groq') {
            $this->providerEndpoint = 'https://api.groq.com/openai/v1';
        } elseif ($val === 'openai_compatible') {
            $this->providerEndpoint = 'https://api.openai.com/v1';
        } elseif ($val === 'anthropic') {
            $this->providerEndpoint = 'https://api.anthropic.com/v1';
        } elseif ($val === 'gemini') {
            $this->providerEndpoint = 'https://generativelanguage.googleapis.com/v1beta';
        } elseif ($val === 'openrouter') {
            $this->providerEndpoint = 'https://openrouter.ai/api/v1';
        }
    }

    public function saveProvider()
    {
        $this->validate([
            'providerName' => 'required|string|max:100',
            'providerDriver' => 'required|string',
            'providerEndpoint' => 'required|url',
        ]);

        if ($this->editingProviderId) {
            $provider = AiProvider::find($this->editingProviderId);
            if ($provider) {
                $provider->update([
                    'name' => $this->providerName,
                    'driver' => $this->providerDriver,
                    'endpoint' => rtrim($this->providerEndpoint, '/'),
                    'api_key' => !empty($this->providerApiKey) ? $this->providerApiKey : null,
                ]);
                $this->dispatch('toast', title: 'AI Provider Updated', message: "Updated {$provider->name} successfully.", type: 'success');
            }
        } else {
            $provider = AiProvider::create([
                'name' => $this->providerName,
                'driver' => $this->providerDriver,
                'endpoint' => rtrim($this->providerEndpoint, '/'),
                'api_key' => !empty($this->providerApiKey) ? $this->providerApiKey : null,
                'status' => 'active',
            ]);
            $this->dispatch('toast', title: 'AI Provider Added', message: "Added {$provider->name} successfully.", type: 'success');
        }

        $this->selectedProviderId = $provider->id ?? $this->selectedProviderId;
        $this->dispatch('close-modals');
        $this->showProviderModal = false;
        $this->editingProviderId = null;
        $this->providerName = '';
        $this->providerApiKey = '';
    }

    public function deleteProvider($id)
    {
        $provider = AiProvider::find($id);
        if ($provider) {
            $name = $provider->name;
            $provider->delete();
            $this->dispatch('toast', title: 'Provider Removed', message: "Removed {$name} and its configuration.", type: 'warning');
        }
    }

    public function saveModel()
    {
        $this->validate([
            'selectedProviderId' => 'required|exists:ai_providers,id',
            'modelName' => 'required|string|max:100',
            'modelIdentifier' => 'required|string|max:100',
        ]);

        AiModel::create([
            'ai_provider_id' => $this->selectedProviderId,
            'name' => $this->modelName,
            'model_id' => $this->modelIdentifier,
            'temperature' => $this->temperature,
            'context_length' => $this->contextLength,
            'max_output_tokens' => 2048,
            'timeout_seconds' => 120,
            'status' => AIModelStatus::Active,
        ]);

        $this->dispatch('close-modals');
        $this->showModelModal = false;
        $this->modelName = '';
        $this->modelIdentifier = '';

        $this->dispatch('toast', title: 'AI Model Registered', message: 'New AI Model added to active catalog.', type: 'success');
    }

    public function testConnection($providerId)
    {
        $provider = AiProvider::find($providerId);
        if (!$provider) return;

        try {
            $endpoint = rtrim($provider->endpoint, '/');
            $apiKey = $provider->api_key;
            $driverValue = is_object($provider->driver) ? $provider->driver->value : (string)$provider->driver;

            if ($driverValue === 'ollama') {
                $res = Http::timeout(5)->get($endpoint . '/api/tags');
                if ($res->successful()) {
                    $this->dispatch('toast', title: 'Ollama Test Passed', message: 'Local Ollama server is active & reachable!', type: 'success');
                } else {
                    $this->dispatch('toast', title: 'Ollama Connection Error', message: 'Ollama server responded with HTTP status ' . $res->status(), type: 'danger');
                }
            } elseif (in_array($driverValue, ['groq', 'openai_compatible', 'openrouter'])) {
                $res = Http::timeout(8)
                    ->withoutVerifying()
                    ->withHeaders(['Authorization' => 'Bearer ' . $apiKey])
                    ->get($endpoint . '/models');

                if ($res->successful()) {
                    $this->dispatch('toast', title: 'API Key Verified! ⚡', message: "Handshake successful with {$provider->name}. Connection OK!", type: 'success');
                } else {
                    $msg = $res->json()['error']['message'] ?? ('HTTP Status ' . $res->status() . ' - Invalid API Key or Unauthorized');
                    $this->dispatch('toast', title: 'API Handshake Failed', message: $msg, type: 'danger');
                }
            } elseif ($driverValue === 'anthropic') {
                $res = Http::timeout(8)
                    ->withoutVerifying()
                    ->withHeaders([
                        'x-api-key' => $apiKey,
                        'anthropic-version' => '2023-06-01',
                    ])
                    ->get($endpoint . '/models');

                if ($res->successful()) {
                    $this->dispatch('toast', title: 'Claude API Verified! ⚡', message: "Verified Anthropic API Key for {$provider->name}.", type: 'success');
                } else {
                    $msg = $res->json()['error']['message'] ?? ('HTTP Status ' . $res->status());
                    $this->dispatch('toast', title: 'Claude API Error', message: $msg, type: 'danger');
                }
            } elseif ($driverValue === 'gemini') {
                $res = Http::timeout(8)
                    ->withoutVerifying()
                    ->get($endpoint . '/models?key=' . $apiKey);

                if ($res->successful()) {
                    $this->dispatch('toast', title: 'Gemini API Verified! ⚡', message: "Verified Google Gemini API Key for {$provider->name}.", type: 'success');
                } else {
                    $msg = $res->json()['error']['message'] ?? ('HTTP Status ' . $res->status());
                    $this->dispatch('toast', title: 'Gemini API Error', message: $msg, type: 'danger');
                }
            } else {
                $this->dispatch('toast', title: 'Endpoint Checked', message: "Endpoint for {$provider->name} is saved.", type: 'success');
            }
        } catch (\Exception $e) {
            $this->dispatch('toast', title: 'Connection Handshake Error', message: $e->getMessage(), type: 'danger');
        }
    }

    public function deleteModel($id)
    {
        AiModel::destroy($id);
        $this->dispatch('toast', title: 'Model Removed', message: 'AI model removed from active registry.', type: 'info');
    }

    public function render()
    {
        $providers = AiProvider::with('models')->get();
        $models = AiModel::with('provider')->latest()->get();

        return view('livewire.ai.models-index', [
            'providers' => $providers,
            'models' => $models,
        ]);
    }
}
