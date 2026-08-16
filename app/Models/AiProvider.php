<?php

namespace App\Models;

use App\Enums\AIProvider as AIProviderEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiProvider extends Model
{
    protected $table = 'ai_providers';

    protected $fillable = [
        'name',
        'driver',
        'endpoint',
        'api_key',
        'extra_config',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'api_key'      => 'encrypted',
            'extra_config' => 'array',
            'driver'       => AIProviderEnum::class,
        ];
    }

    public function models(): HasMany
    {
        return $this->hasMany(AiModel::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
