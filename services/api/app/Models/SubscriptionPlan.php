<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'currency',
        'billing_interval',
        'billing_interval_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'billing_interval_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function tenantSubscriptions(): HasMany
    {
        return $this->hasMany(
            TenantSubscription::class,
            'subscription_plan_id'
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

