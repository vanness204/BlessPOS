<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlatformAdmin extends Authenticatable
{
    protected $table = 'platform_admins';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function verifiedSubscriptionPayments(): HasMany
    {
        return $this->hasMany(
            SubscriptionPayment::class,
            'verified_by'
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
