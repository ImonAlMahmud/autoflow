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
        'role',
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

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin' || $this->email === 'admin@autoflow.local';
    }

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }

    public function canAddWebsite(): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return $this->websites()->count() < $this->websites_limit;
    }

    public function hasActiveSubscription(): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return !empty($this->plan) && $this->plan !== 'none' && $this->plan_status === 'active';
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
        if (!$this->hasActiveSubscription()) {
            return 'NO SUBSCRIPTION (INACTIVE)';
        }

        return match ($this->plan) {
            'pro' => 'PRO AGENCY ($79/mo)',
            'enterprise' => 'ENTERPRISE ($199/mo)',
            default => 'STARTER PLAN ($29/mo)',
        };
    }
}
