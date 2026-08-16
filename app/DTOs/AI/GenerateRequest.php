<?php

namespace App\DTOs\AI;

readonly class GenerateRequest
{
    public function __construct(
        public string $systemPrompt,
        public string $instructions,
        public array  $segments,          // ContentSegment DTOs as arrays
        public string $modelId,
        public float  $temperature = 0.7,
        public ?int   $maxOutputTokens = null,
        public ?int   $contextLength = null,
        public bool   $structuredOutput = true,
        public array  $protectedValues = [],
    ) {}

    public function toArray(): array
    {
        return [
            'systemPrompt'    => $this->systemPrompt,
            'instructions'    => $this->instructions,
            'segments'        => $this->segments,
            'modelId'         => $this->modelId,
            'temperature'     => $this->temperature,
            'maxOutputTokens' => $this->maxOutputTokens,
            'structuredOutput'=> $this->structuredOutput,
        ];
    }
}
