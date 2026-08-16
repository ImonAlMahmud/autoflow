<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidationResult extends Model
{
    protected $fillable = [
        'rewrite_job_id',
        'json_validity',
        'segment_completeness',
        'protected_values',
        'html_structure',
        'links_preserved',
        'word_count',
        'language_check',
        'content_quality',
        'overall_passed',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'json_validity'        => 'boolean',
            'segment_completeness' => 'boolean',
            'protected_values'     => 'boolean',
            'html_structure'       => 'boolean',
            'links_preserved'      => 'boolean',
            'word_count'           => 'boolean',
            'language_check'       => 'boolean',
            'content_quality'      => 'boolean',
            'overall_passed'       => 'boolean',
            'details'              => 'array',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(RewriteJob::class, 'rewrite_job_id');
    }
}
