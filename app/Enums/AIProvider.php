<?php

namespace App\Enums;

enum AIProvider: string
{
    case Ollama            = 'ollama';
    case Groq              = 'groq';
    case OpenAICompatible  = 'openai_compatible';
    case OpenRouter        = 'openrouter';
    case Anthropic         = 'anthropic';
    case Gemini            = 'gemini';

    public function label(): string
    {
        return match($this) {
            self::Ollama           => 'Ollama (Local)',
            self::Groq             => 'Groq Cloud API',
            self::OpenAICompatible => 'OpenAI Compatible',
            self::OpenRouter       => 'OpenRouter',
            self::Anthropic        => 'Anthropic',
            self::Gemini           => 'Google Gemini',
        };
    }

    public function supportsStructuredOutput(): bool
    {
        return in_array($this, [
            self::Ollama,
            self::OpenAICompatible,
            self::OpenRouter,
        ]);
    }
}
