<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPayment extends Model
{
    public const METHOD_BANK_TRANSFER = 'transfer_bank';
    public const METHOD_QRIS = 'qris';

    public const STATUS_PENDING = 'pending';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'tenant_id',
        'tenant_subscription_id',
        'payment_number',
        'amount',
        'currency',
        'payment_method',
        'status',
        'bank_name',
        'account_holder',
        'reference_number',
        'proof_path',
        'proof_disk',
        'verified_by',
        'verified_at',
        'rejection_reason',
        'paid_at',
        'refunded_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'verified_at' => 'datetime',
            'paid_at' => 'datetime',
            'refunded_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function tenantSubscription(): BelongsTo
    {
        return $this->belongsTo(
            TenantSubscription::class,
            'tenant_subscription_id'
        );
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(
            PlatformAdmin::class,
            'verified_by'
        );
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }
}

