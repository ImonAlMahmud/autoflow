<?php

namespace App\Models;

use App\Enums\JobStatus;
use App\Enums\TriggerType;
use App\Enums\ValidationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class RewriteJob extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'website_id',
        'website_page_id',
        'ai_model_id',
        'prompt_version_id',
        'trigger_type',
        'status',
        'original_commit_hash',
        'original_content_hash',
        'rewritten_content_hash',
        'original_word_count',
        'new_word_count',
        'validation_status',
        'commit_hash',
        'workspace_path',
        'queue_job_id',
        'failure_reason',
        'reviewer_notes',
        'started_at',
        'scheduled_at',
        'finished_at',
        'duration_seconds',
    ];

    protected function casts(): array
    {
        return [
            'status'            => JobStatus::class,
            'trigger_type'      => TriggerType::class,
            'validation_status' => ValidationStatus::class,
            'started_at'        => 'datetime',
            'scheduled_at'      => 'datetime',
            'finished_at'       => 'datetime',
        ];
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(WebsitePage::class, 'website_page_id');
    }

    public function aiModel(): BelongsTo
    {
        return $this->belongsTo(AiModel::class);
    }

    public function promptVersion(): BelongsTo
    {
        return $this->belongsTo(PromptVersion::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(RewriteResult::class);
    }

    public function validationResult(): HasOne
    {
        return $this->hasOne(ValidationResult::class);
    }

    public function gitOperations(): HasMany
    {
        return $this->hasMany(GitOperation::class);
    }

    public function isTerminal(): bool
    {
        return $this->status->isTerminal();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function getDurationAttribute(): ?int
    {
        if ($this->started_at && $this->finished_at) {
            return $this->finished_at->diffInSeconds($this->started_at);
        }
        return null;
    }
}
