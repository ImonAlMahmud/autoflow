<?php

namespace App\Models;

use App\Enums\GitOperationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GitOperation extends Model
{
    protected $fillable = [
        'website_id',
        'rewrite_job_id',
        'operation',
        'status',
        'commit_hash',
        'branch',
        'message',
        'error',
        'duration_ms',
    ];

    protected function casts(): array
    {
        return [
            'operation' => GitOperationType::class,
        ];
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function rewriteJob(): BelongsTo
    {
        return $this->belongsTo(RewriteJob::class);
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }
}
