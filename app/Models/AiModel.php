<?php

namespace App\Models;

use App\Enums\AIModelStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiModel extends Model
{
    protected $table = 'ai_models';

    protected $fillable = [
        'ai_provider_id',
        'name',
        'model_id',
        'temperature',
        'context_length',
        'max_output_tokens',
        'timeout_seconds',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'temperature'       => 'decimal:2',
            'status'            => AIModelStatus::class,
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }

    public function rewriteJobs(): HasMany
    {
        return $this->hasMany(RewriteJob::class);
    }

    public function isActive(): bool
    {
        return $this->status === AIModelStatus::Active;
    }
}
