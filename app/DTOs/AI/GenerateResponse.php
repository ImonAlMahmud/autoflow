<?php

namespace App\DTOs\AI;

readonly class GenerateResponse
{
    public function __construct(
        public bool   $success,
        public array  $segments,          // [{id, original, rewritten}]
        public string $rawResponse,
        public ?int   $requestTokens = null,
        public ?int   $responseTokens = null,
        public ?int   $durationMs = null,
        public ?string $error = null,
    ) {}

    public static function failure(string $error, string $rawResponse = ''): self
    {
        return new self(
            success: false,
            segments: [],
            rawResponse: $rawResponse,
            error: $error,
        );
    }
}
