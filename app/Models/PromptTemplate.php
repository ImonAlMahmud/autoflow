<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PromptTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'language',
        'status',
    ];

    public function versions(): HasMany
    {
        return $this->hasMany(PromptVersion::class);
    }

    public function currentVersion(): HasOne
    {
        return $this->hasOne(PromptVersion::class)->where('is_current', true);
    }

    public function latestVersion(): HasOne
    {
        return $this->hasOne(PromptVersion::class)->latestOfMany();
    }
}
