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

    // Add / Edit Custom Model Form
    public ?int $editingModelId = null;
    public ?int $selectedProviderId = null;
    public string $modelName = '';
    public string $modelIdentifier = '';
    public float $temperature = 0.75;
    public int $contextLength = 8192;

    public bool $showProviderModal = false;
    public bool $showModelModal = false;
    public array $testResults = [];

    public function mount()
    {
        $user = auth()->user();
        $firstProvider = null;
        if ($user && $user->isSuperAdmin()) {
            $firstProvider = AiProvider::first();
        } elseif ($user) {
            $firstProvider = AiProvider::where('user_id', $user->id)->first();
        }
        
        if ($firstProvider) {
            $this->selectedProviderId = $firstProvider->id;
        }
    }

    public function openNewModelModal()
    {
        $user = auth()->user();
        $query = AiModel::query();
        if ($user && !$user->isSuperAdmin()) {
            $query->whereHas('provider', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($query->count() >= 10) {
            $this->dispatch('toast', title: 'Limit Reached', message: 'Maximum limit of 10 AI models reached. Please remove an existing model to add a new one.', type: 'warning');
            return;
        }

        $this->editingModelId = null;
        $this->modelName = '';
        $this->modelIdentifier = '';
        $this->temperature = 0.75;
        $this->contextLength = 8192;
        $this->showModelModal = true;
    }

    public function editModel($id)
    {
        $model = AiModel::find($id);
        if (!$model) return;

        $this->editingModelId = $model->id;
        $this->selectedProviderId = $model->ai_provider_id;
        $this->modelName = $model->name;
        $this->modelIdentifier = $model->model_id;
        $this->temperature = (float)($model->temperature ?? 0.75);
        $this->contextLength = (int)($model->context_length ?? 8192);
        $this->showModelModal = true;
    }

    public function saveModel()
    {
        $this->validate([
            'selectedProviderId' => 'required|exists:ai_providers,id',
            'modelName' => 'required|string|max:100',
            'modelIdentifier' => 'required|string|max:100',
        ]);

        if ($this->editingModelId) {
            $model = AiModel::find($this->editingModelId);
            if ($model) {
                $model->update([
                    'ai_provider_id' => $this->selectedProviderId,
                    'name' => $this->modelName,
                    'model_id' => $this->modelIdentifier,
                    'temperature' => $this->temperature,
                    'context_length' => $this->contextLength,
                ]);
                $this->dispatch('toast', title: 'AI Model Updated', message: "Updated {$model->name} successfully.", type: 'success');
            }
        } else {
            $user = auth()->user();
            $query = AiModel::query();
            if ($user && !$user->isSuperAdmin()) {
                $query->whereHas('provider', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }

            if ($query->count() >= 10) {
                $this->dispatch('toast', title: 'Limit Reached', message: 'Maximum limit of 10 AI models reached.', type: 'error');
                return;
            }

            $model = AiModel::create([
                'ai_provider_id' => $this->selectedProviderId,
                'name' => $this->modelName,
                'model_id' => $this->modelIdentifier,
                'temperature' => $this->temperature,
                'context_length' => $this->contextLength,
                'max_output_tokens' => 4096,
                'timeout_seconds' => 120,
                'status' => AIModelStatus::Active,
            ]);
            $this->dispatch('toast', title: 'AI Model Registered', message: 'New AI Model added to active catalog.', type: 'success');
        }

        $this->dispatch('close-modals');
        $this->showModelModal = false;
        $this->editingModelId = null;
        $this->modelName = '';
        $this->modelIdentifier = '';
    }

    public function openNewProviderModal()
    {
        $user = auth()->user();
        $query = AiProvider::query();
        if ($user && !$user->isSuperAdmin()) {
            $query->where('user_id', $user->id);
        }

        if ($query->count() >= 3) {
            $this->dispatch('toast', title: 'Limit Reached', message: 'Maximum limit of 3 AI Providers reached. Please remove an existing provider to add a new one.', type: 'warning');
            return;
        }

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
            $user = auth()->user();
            $query = AiProvider::query();
            if ($user && !$user->isSuperAdmin()) {
                $query->where('user_id', $user->id);
            }

            if ($query->count() >= 3) {
                $this->dispatch('toast', title: 'Limit Reached', message: 'Maximum limit of 3 AI Providers reached.', type: 'error');
                return;
            }

            $provider = AiProvider::create([
                'user_id' => $user?->id ?? 1,
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

    public function testConnection($providerId)
    {
        $provider = AiProvider::find($providerId);
        if (!$provider) return;

        try {
            $endpoint = rtrim($provider->endpoint, '/');
            $apiKey = $provider->api_key;
            $driverValue = is_object($provider->driver) ? $provider->driver->value : (string)$provider->driver;

            if ($driverValue === 'ollama') {
                $res = Http::timeout(5)->withoutVerifying()->get($endpoint . '/api/tags');
                if ($res->successful()) {
                    $modelsCount = count($res->json()['models'] ?? []);
                    $this->testResults[$providerId] = ['status' => 'success', 'message' => "Ollama Online ({$modelsCount} local models available)"];
                    $this->dispatch('toast', title: 'Ollama Server Connected! ⚡', message: "Found {$modelsCount} models in your local Ollama instance.", type: 'success');
                } else {
                    $this->testResults[$providerId] = ['status' => 'error', 'message' => 'Ollama returned HTTP ' . $res->status()];
                    $this->dispatch('toast', title: 'Ollama Connection Failed', message: 'Could not reach ' . $endpoint, type: 'danger');
                }
            } elseif ($driverValue === 'groq' || $driverValue === 'openai_compatible' || $driverValue === 'openrouter') {
                $res = Http::timeout(8)
                    ->withoutVerifying()
                    ->withToken($apiKey)
                    ->get($endpoint . '/models');

                if ($res->successful()) {
                    $this->testResults[$providerId] = ['status' => 'success', 'message' => 'Handshake Successful (200 OK)'];
                    $this->dispatch('toast', title: 'Cloud API Verified! ⚡', message: "Verified API Key & Endpoint for {$provider->name}.", type: 'success');
                } else {
                    $msg = $res->json()['error']['message'] ?? ('HTTP Status ' . $res->status());
                    $this->testResults[$providerId] = ['status' => 'error', 'message' => $msg];
                    $this->dispatch('toast', title: 'API Handshake Error', message: $msg, type: 'danger');
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
                    $this->testResults[$providerId] = ['status' => 'success', 'message' => 'Claude API Key Verified (200 OK)'];
                    $this->dispatch('toast', title: 'Claude API Verified! ⚡', message: "Verified Anthropic API Key for {$provider->name}.", type: 'success');
                } else {
                    $msg = $res->json()['error']['message'] ?? ('HTTP Status ' . $res->status());
                    $this->testResults[$providerId] = ['status' => 'error', 'message' => $msg];
                    $this->dispatch('toast', title: 'Claude API Error', message: $msg, type: 'danger');
                }
            } elseif ($driverValue === 'gemini') {
                $res = Http::timeout(8)
                    ->withoutVerifying()
                    ->get($endpoint . '/models?key=' . $apiKey);

                if ($res->successful()) {
                    $this->testResults[$providerId] = ['status' => 'success', 'message' => 'Gemini API Key Verified (200 OK)'];
                    $this->dispatch('toast', title: 'Gemini API Verified! ⚡', message: "Verified Google Gemini API Key for {$provider->name}.", type: 'success');
                } else {
                    $msg = $res->json()['error']['message'] ?? ('HTTP Status ' . $res->status());
                    $this->testResults[$providerId] = ['status' => 'error', 'message' => $msg];
                    $this->dispatch('toast', title: 'Gemini API Error', message: $msg, type: 'danger');
                }
            } else {
                $this->testResults[$providerId] = ['status' => 'success', 'message' => 'Endpoint saved.'];
                $this->dispatch('toast', title: 'Endpoint Checked', message: "Endpoint for {$provider->name} is saved.", type: 'success');
            }
        } catch (\Exception $e) {
            $this->testResults[$providerId] = ['status' => 'error', 'message' => $e->getMessage()];
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
        $user = auth()->user();
        $providersQuery = AiProvider::with('models');
        $modelsQuery = AiModel::with('provider')->latest();

        if ($user && !$user->isSuperAdmin()) {
            // User sees:
            // 1. Their own created Providers (user_id == user->id)
            // 2. Global System Providers provided by Super Admin (user_id IS NULL or owned by superadmin)
            $providersQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id')
                  ->orWhereHas('user', fn($sq) => $sq->where('role', 'superadmin'));
            });

            $modelsQuery->whereHas('provider', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id')
                  ->orWhereHas('user', fn($sq) => $sq->where('role', 'superadmin'));
            });
        }

        $providers = $providersQuery->get();
        $models = $modelsQuery->get();

        return view('livewire.ai.models-index', [
            'providers' => $providers,
            'models' => $models,
        ]);
    }
}
