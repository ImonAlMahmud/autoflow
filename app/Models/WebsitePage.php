<?php

namespace App\Models;

use App\Enums\ApprovalMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebsitePage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'website_id',
        'path',
        'friendly_name',
        'rewrite_enabled',
        'rewrite_interval_days',
        'ai_model_id',
        'prompt_version_id',
        'rewrite_scope',
        'protected_values',
        'excluded_selectors',
        'approval_mode',
        'content_hash',
        'last_rewrite_at',
        'next_rewrite_at',
    ];

    protected function casts(): array
    {
        return [
            'rewrite_enabled'   => 'boolean',
            'rewrite_scope'     => 'array',
            'protected_values'  => 'array',
            'excluded_selectors'=> 'array',
            'approval_mode'     => ApprovalMode::class,
            'last_rewrite_at'   => 'datetime',
            'next_rewrite_at'   => 'datetime',
        ];
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function aiModel(): BelongsTo
    {
        return $this->belongsTo(AiModel::class);
    }

    public function promptVersion(): BelongsTo
    {
        return $this->belongsTo(PromptVersion::class);
    }

    public function rewriteJobs(): HasMany
    {
        return $this->hasMany(RewriteJob::class);
    }

    public function latestJob(): HasOne
    {
        return $this->hasOne(RewriteJob::class)->latestOfMany();
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->friendly_name ?? $this->path;
    }

    public function getEffectiveIntervalDaysAttribute(): int
    {
        return $this->rewrite_interval_days ?? $this->website->default_rewrite_interval_days;
    }

    public function getEffectiveApprovalModeAttribute(): ApprovalMode
    {
        return $this->approval_mode ?? $this->website->approval_mode;
    }

    public function isDue(): bool
    {
        return $this->rewrite_enabled
            && $this->next_rewrite_at !== null
            && $this->next_rewrite_at->isPast();
    }
}
