<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionInvoice extends Model
{
    public const STATUS_ISSUED = 'issued';
    public const STATUS_PAID = 'paid';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_VOID = 'void';

    protected $fillable = [
        'tenant_id',
        'tenant_subscription_id',
        'subscription_payment_id',
        'invoice_number',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'currency',
        'status',
        'issued_at',
        'due_at',
        'paid_at',
        'billing_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'discount_amount' => 'integer',
            'tax_amount' => 'integer',
            'total_amount' => 'integer',
            'issued_at' => 'datetime',
            'due_at' => 'datetime',
            'paid_at' => 'datetime',
            'billing_snapshot' => 'array',
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

    public function subscriptionPayment(): BelongsTo
    {
        return $this->belongsTo(
            SubscriptionPayment::class,
            'subscription_payment_id'
        );
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE
            || (
                $this->status === self::STATUS_ISSUED
                && $this->due_at !== null
                && $this->due_at->isPast()
            );
    }
}
