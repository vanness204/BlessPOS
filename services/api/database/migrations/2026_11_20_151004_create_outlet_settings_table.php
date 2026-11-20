
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlet_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('shift_enabled')->default(false);

            $table->foreignId('outlet_id')
                ->unique()
                ->constrained('outlets')
                ->cascadeOnDelete();

            // Pengaturan operasional
            $table->boolean('custom_closing_time_enabled')->default(false);
            $table->time('closing_time')->nullable();
            $table->boolean('custom_price_discount_enabled')->default(false);

            // Pajak penjualan
            $table->boolean('sales_tax_enabled')->default(false);
            $table->decimal('sales_tax_rate', 8, 4)->default(0);
            $table->boolean('tax_after_discount')->default(false);

            // Service charge
            $table->boolean('service_charge_enabled')->default(false);
            $table->decimal('service_charge_rate', 8, 4)->default(0);
            $table->boolean('service_charge_before_discount')->default(false);

            // Pajak service charge
            $table->boolean('service_charge_tax_enabled')->default(false);
            $table->string('service_charge_tax_name')->nullable();
            $table->decimal('service_charge_tax_rate', 8, 4)->default(0);

            // Akun pembayaran tunai
            $table->string('cash_payment_account_name')->default('Kas');

            // Catatan struk
            $table->text('receipt_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outlet_settings');
    }
};
