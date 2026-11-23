<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outlet extends Model
{
    use SoftDeletes;

    protected $table = 'outlets';

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'type',
        'default_warehouse_id',
        'category',
        'phone',
        'email',
        'currency',
        'logo',
        'address',
        'city',
        'province',
        'postal_code',
        'latitude',
        'longitude',
        'timezone',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(OutletSetting::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_outlets')
            ->withPivot(['id', 'role_id', 'is_active'])
            ->withTimestamps();
    }

    public function activeUsers(): BelongsToMany
    {
        return $this->users()
            ->wherePivot('is_active', true);
    }
}