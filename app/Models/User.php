<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'plan',
        'plan_status',
        'websites_limit',
        'monthly_rewrites_limit',
        'rewrites_used_this_month',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
            'websites_limit'    => 'integer',
            'monthly_rewrites_limit' => 'integer',
            'rewrites_used_this_month' => 'integer',
        ];
    }

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }

    public function canAddWebsite(): bool
    {
        return $this->websites()->count() < $this->websites_limit;
    }

    public function isStarter(): bool
    {
        return $this->plan === 'starter';
    }

    public function isPro(): bool
    {
        return $this->plan === 'pro';
    }

    public function isEnterprise(): bool
    {
        return $this->plan === 'enterprise';
    }

    public function getPlanBadgeAttribute(): string
    {
        return match ($this->plan) {
            'pro' => 'PRO AGENCY ($79/mo)',
            'enterprise' => 'ENTERPRISE ($199/mo)',
            default => 'STARTER PLAN ($29/mo)',
        };
    }
}
