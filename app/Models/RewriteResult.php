<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewriteResult extends Model
{
    protected $fillable = [
        'rewrite_job_id',
        'original_segments',
        'rewritten_segments',
        'diff_data',
        'original_html_hash',
        'rewritten_html_hash',
        'ai_request_tokens',
        'ai_response_tokens',
        'ai_duration_ms',
    ];

    protected function casts(): array
    {
        return [
            'original_segments'  => 'array',
            'rewritten_segments' => 'array',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(RewriteJob::class, 'rewrite_job_id');
    }
}
