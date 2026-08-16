<?php

namespace App\Models;

use App\Enums\ApprovalMode;
use App\Enums\GitAuthMethod;
use App\Enums\WebsiteStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Website extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'domain',
        'git_repository_url',
        'git_branch',
        'git_auth_method',
        'git_access_token',
        'git_author_name',
        'git_author_email',
        'git_ssh_key_path',
        'local_production_path',
        'default_ai_model_id',
        'default_prompt_version_id',
        'default_rewrite_interval_days',
        'default_rewrite_interval_unit',
        'language',
        'timezone',
        'auto_push_enabled',
        'approval_mode',
        'notification_email',
        'status',
        'global_exclusion_selectors',
        'protected_terms',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'git_auth_method'             => GitAuthMethod::class,
            'git_access_token'            => 'encrypted',
            'git_ssh_key_path'            => 'encrypted',
            'approval_mode'               => ApprovalMode::class,
            'status'                      => WebsiteStatus::class,
            'auto_push_enabled'           => 'boolean',
            'global_exclusion_selectors'  => 'array',
            'protected_terms'             => 'array',
            'last_synced_at'              => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(WebsitePage::class);
    }

    public function rewriteJobs(): HasMany
    {
        return $this->hasMany(RewriteJob::class);
    }

    public function gitOperations(): HasMany
    {
        return $this->hasMany(GitOperation::class);
    }

    public function defaultAiModel(): BelongsTo
    {
        return $this->belongsTo(AiModel::class, 'default_ai_model_id');
    }

    public function defaultPromptVersion(): BelongsTo
    {
        return $this->belongsTo(PromptVersion::class, 'default_prompt_version_id');
    }

    public function isActive(): bool
    {
        return $this->status === WebsiteStatus::Active;
    }

    public function enabledPages(): HasMany
    {
        return $this->pages()->where('rewrite_enabled', true);
    }
}
