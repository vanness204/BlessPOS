<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutletSetting extends Model
{
    protected $table = 'outlet_settings';

    protected $fillable = [
        'outlet_id',
        'shift_enabled',
        'custom_closing_time_enabled',
        'closing_time',
        'custom_price_discount_enabled',
        'sales_tax_enabled',
        'sales_tax_rate',
        'tax_after_discount',
        'service_charge_enabled',
        'service_charge_rate',
        'service_charge_before_discount',
        'service_charge_tax_enabled',
        'service_charge_tax_name',
        'service_charge_tax_rate',
        'cash_payment_account_name',
        'receipt_note',
    ];

    protected function casts(): array
    {
        return [
            'shift_enabled' => 'boolean',
            'custom_closing_time_enabled' => 'boolean',
            'custom_price_discount_enabled' => 'boolean',
            'sales_tax_enabled' => 'boolean',
            'sales_tax_rate' => 'decimal:4',
            'tax_after_discount' => 'boolean',
            'service_charge_enabled' => 'boolean',
            'service_charge_rate' => 'decimal:4',
            'service_charge_before_discount' => 'boolean',
            'service_charge_tax_enabled' => 'boolean',
            'service_charge_tax_rate' => 'decimal:4',
        ];
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }
}
