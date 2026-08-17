<?php

namespace App\Models;

use App\Enums\AIProvider as AIProviderEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiProvider extends Model
{
    protected $table = 'ai_providers';

    protected $fillable = [
        'user_id',
        'name',
        'driver',
        'endpoint',
        'api_key',
        'extra_config',
        'status',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
