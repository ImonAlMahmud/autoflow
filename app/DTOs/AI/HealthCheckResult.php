<?php

namespace App\DTOs\AI;

readonly class HealthCheckResult
{
    public function __construct(
        public bool   $online,
        public ?int   $responseTimeMs = null,
        public ?string $error = null,
        public array  $availableModels = [],
        public ?string $version = null,
    ) {}

    public static function offline(string $error): self
    {
        return new self(
            online: false,
            error: $error,
        );
    }
}
