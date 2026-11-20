<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'foodcourt_id',
        'name',
        'slug',
        'owner_name',
        'email',
        'phone',
        'address',
        'logo',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function foodcourt(): BelongsTo
    {
        return $this->belongsTo(Foodcourt::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}