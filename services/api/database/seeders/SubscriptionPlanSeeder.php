<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subscription_plans')->updateOrInsert(
            [
                'code' => 'BLESS-ALL-IN-ONE',
            ],
            [
                'name' => 'Bless POS All in One',
                'description' => 'Paket lengkap Bless POS dengan seluruh fitur tersedia, termasuk POS, inventory, purchasing, cash management, expense management, reporting, attendance, shift, dan notification.',
                'price' => 2500000,
                'currency' => 'IDR',
                'billing_interval' => 'yearly',
                'billing_interval_count' => 1,
                'is_active' => true,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
