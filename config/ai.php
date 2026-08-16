<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    */
    'default_provider' => env('AI_DEFAULT_PROVIDER', 'ollama'),

    /*
    |--------------------------------------------------------------------------
    | AI Timeout (seconds)
    |--------------------------------------------------------------------------
    */
    'timeout' => (int) env('AI_TIMEOUT', 120),

    /*
    |--------------------------------------------------------------------------
    | Default Temperature
    |--------------------------------------------------------------------------
    */
    'default_temperature' => (float) env('AI_DEFAULT_TEMPERATURE', 0.7),

    /*
    |--------------------------------------------------------------------------
    | Ollama Configuration
    |--------------------------------------------------------------------------
    */
    'ollama' => [
        'url'     => env('OLLAMA_URL', 'http://127.0.0.1:11434'),
        'timeout' => (int) env('OLLAMA_TIMEOUT', 120),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rewrite Content Quality Thresholds
    |--------------------------------------------------------------------------
    */
    'rewrite' => [
        'max_expansion_percent'  => (int) env('REWRITE_MAX_EXPANSION_PERCENT', 30),
        'max_reduction_percent'  => (int) env('REWRITE_MAX_REDUCTION_PERCENT', 25),
        'min_word_length'        => 3,
        'placeholder_patterns'   => [
            '/\[INSERT/i',
            '/\[YOUR/i',
            '/lorem ipsum/i',
            '/placeholder/i',
        ],
        'markdown_fence_pattern' => '/^```/m',
        'ai_commentary_patterns' => [
            '/^here is/i',
            '/^certainly/i',
            '/^of course/i',
            '/^as requested/i',
            '/^i\'ve rewritten/i',
        ],
    ],

];
