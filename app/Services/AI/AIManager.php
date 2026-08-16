<?php

namespace App\Services\AI;

use App\DTOs\AI\GenerateRequest;
use App\DTOs\AI\GenerateResponse;
use App\DTOs\AI\HealthCheckResult;
use App\Enums\AIProvider;
use App\Models\AiProvider;
use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Providers\OllamaProvider;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class AIManager
{
    private array $resolvedProviders = [];

    public function __construct(
        private readonly OllamaProvider $ollamaProvider,
    ) {}

    /**
     * Get a provider instance for the given driver.
     */
    public function provider(string $driver): AIProviderInterface
    {
        if (isset($this->resolvedProviders[$driver])) {
            return $this->resolvedProviders[$driver];
        }

        $instance = match($driver) {
            AIProvider::Ollama->value => $this->ollamaProvider,
            default                   => throw new RuntimeException("AI provider driver [{$driver}] is not supported."),
        };

        $this->resolvedProviders[$driver] = $instance;
        return $instance;
    }

    /**
     * Get provider for a specific AiProvider model.
     */
    public function forModel(AiProvider $provider): AIProviderInterface
    {
        return $this->provider($provider->driver->value);
    }

    /**
     * Generate content using the default or specified provider.
     */
    public function generate(GenerateRequest $request, string $driver = null): GenerateResponse
    {
        $driver ??= config('ai.default_provider', 'ollama');

        try {
            return $this->provider($driver)->generate($request);
        } catch (\Throwable $e) {
            Log::error('AI generation failed', [
                'driver' => $driver,
                'error'  => $e->getMessage(),
            ]);
            return GenerateResponse::failure($e->getMessage());
        }
    }

    /**
     * Check health of all active providers.
     */
    public function healthCheckAll(): array
    {
        $results = [];

        foreach (AIProvider::cases() as $provider) {
            try {
                $instance = $this->provider($provider->value);
                $results[$provider->value] = $instance->healthCheck();
            } catch (\Throwable $e) {
                $results[$provider->value] = HealthCheckResult::offline($e->getMessage());
            }
        }

        return $results;
    }
}
