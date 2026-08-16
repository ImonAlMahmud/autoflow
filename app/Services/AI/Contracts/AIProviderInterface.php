<?php

namespace App\Services\AI\Contracts;

use App\DTOs\AI\GenerateRequest;
use App\DTOs\AI\GenerateResponse;
use App\DTOs\AI\HealthCheckResult;

interface AIProviderInterface
{
    /**
     * Generate a rewrite response from the AI provider.
     */
    public function generate(GenerateRequest $request): GenerateResponse;

    /**
     * Check if the provider is healthy and responsive.
     */
    public function healthCheck(): HealthCheckResult;

    /**
     * List available models from this provider.
     *
     * @return array<string, mixed>
     */
    public function availableModels(): array;

    /**
     * Whether this provider supports structured JSON output.
     */
    public function supportsStructuredOutput(): bool;

    /**
     * Get the provider driver name.
     */
    public function getDriver(): string;
}
