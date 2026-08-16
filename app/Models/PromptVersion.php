<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromptVersion extends Model
{
    protected $fillable = [
        'prompt_template_id',
        'version',
        'system_prompt',
        'instructions',
        'temperature',
        'is_current',
    ];

    protected function casts(): array
    {
        return [
            'is_current'  => 'boolean',
            'temperature' => 'decimal:2',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(PromptTemplate::class, 'prompt_template_id');
    }

    public function rewriteJobs(): HasMany
    {
        return $this->hasMany(RewriteJob::class);
    }
}
