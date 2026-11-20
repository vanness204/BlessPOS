
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. CUSTOMERS
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('code', 100)->nullable();
            $table->string('name', 180);
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->decimal('opening_balance', 18, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'name']);
            $table->index(['tenant_id', 'phone']);
        });

        // 2. SALES
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outlet_id')->constrained()->restrictOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cashier_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('warehouse_id')->nullable()->constrained()->nullOnDelete();

            $table->string('invoice_number', 100);
            $table->char('currency', 3)->default('IDR');
            $table->uuid('local_id')->nullable();

            $table->string('source', 20)->default('pos');
            $table->string('status', 20)->default('draft');
            $table->string('payment_status', 20)->default('unpaid');

            $table->timestamp('sold_at')->nullable();
            $table->timestamp('synced_at')->nullable();

            $table->string('customer_name', 180)->nullable();
            $table->string('customer_phone', 30)->nullable();

            $table->unsignedInteger('item_count')->default(0);
            $table->decimal('subtotal', 18, 2)->default(0);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('service_charge', 18, 2)->default(0);
            $table->decimal('rounding_amount', 18, 2)->default(0);
            $table->decimal('grand_total', 18, 2)->default(0);
            $table->decimal('paid_amount', 18, 2)->default(0);
            $table->decimal('change_amount', 18, 2)->default(0);
            $table->decimal('refunded_amount', 18, 2)->default(0);

            $table->text('notes')->nullable();
            $table->string('device_identifier', 100)->nullable();

            $table->timestamps();

            $table->unique(['tenant_id', 'invoice_number']);
            $table->unique(['tenant_id', 'local_id']);
            $table->index(['tenant_id', 'outlet_id', 'sold_at']);
            $table->index(['tenant_id', 'status', 'payment_status']);
            $table->index(['tenant_id', 'customer_id']);
        });

        // 3. SALE ITEMS
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();

            $table->string('product_name', 180);
            $table->string('variant_name', 150)->nullable();
            $table->string('sku', 100)->nullable();

            $table->decimal('quantity', 18, 4);
            $table->decimal('unit_price', 18, 2);
            $table->decimal('unit_cost', 18, 2)->default(0);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('subtotal', 18, 2);
            $table->decimal('total', 18, 2);

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'sale_id']);
            $table->index(['tenant_id', 'product_variant_id']);
        });

        // 4. SALE ITEM MODIFIERS
        Schema::create('sale_item_modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modifier_option_id')->nullable()->constrained()->nullOnDelete();

            $table->string('modifier_group_name', 150);
            $table->string('option_name', 150);
            $table->decimal('price_adjustment', 18, 2)->default(0);
            $table->decimal('quantity', 18, 4)->default(1);

            $table->timestamps();

            $table->index(['tenant_id', 'sale_item_id']);
        });

        // 5. PAYMENT METHODS
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();

            $table->string('name', 100);
            $table->string('code', 50);
            $table->string('type', 30);
            $table->string('provider', 100)->nullable();

            $table->boolean('requires_reference')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'is_active']);
        });

        // 6. SALE PAYMENTS
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();

            $table->string('payment_method_name', 100);
            $table->string('payment_type', 30);
            $table->decimal('amount', 18, 2);
            $table->string('reference_number', 150)->nullable();
            $table->string('status', 20)->default('completed');
            $table->timestamp('paid_at')->nullable();

            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'sale_id']);
            $table->index(['tenant_id', 'status']);
        });

        // 7. DISCOUNTS
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();

            $table->string('name', 150);
            $table->string('code', 50)->nullable();
            $table->string('type', 20);
            $table->decimal('value', 18, 2);

            $table->decimal('maximum_discount', 18, 2)->nullable();
            $table->decimal('minimum_purchase', 18, 2)->default(0);

            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('usage_per_customer')->nullable();
            $table->unsignedInteger('used_count')->default(0);

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'is_active']);
        });

        // 8. SALE DISCOUNTS
        Schema::create('sale_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('discount_id')->nullable()->constrained()->nullOnDelete();

            $table->string('discount_name', 150);
            $table->string('discount_type', 20);
            $table->decimal('discount_value', 18, 2);
            $table->decimal('discount_amount', 18, 2);

            $table->timestamps();

            $table->index(['tenant_id', 'sale_id']);
        });

        // 9. SALE REFUNDS
        Schema::create('sale_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained()->restrictOnDelete();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('refund_number', 100);
            $table->string('status', 20)->default('draft');
            $table->string('reason', 255)->nullable();

            $table->decimal('subtotal', 18, 2)->default(0);
            $table->decimal('refund_amount', 18, 2)->default(0);

            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'refund_number']);
            $table->index(['tenant_id', 'sale_id']);
        });

        // 10. SALE REFUND ITEMS
        Schema::create('sale_refund_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_refund_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_item_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();

            $table->string('product_name', 180);
            $table->decimal('quantity', 18, 4);
            $table->decimal('unit_price', 18, 2);
            $table->decimal('refund_amount', 18, 2);
            $table->boolean('restock')->default(false);

            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'sale_refund_id']);
            $table->index(['tenant_id', 'sale_item_id']);
        });

        // 11. SALE REFUND PAYMENTS
        Schema::create('sale_refund_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_refund_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();

            $table->string('payment_method_name', 100);
            $table->string('payment_type', 30);
            $table->decimal('amount', 18, 2);
            $table->string('reference_number', 150)->nullable();
            $table->string('status', 20)->default('completed');

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'sale_refund_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_refund_payments');
        Schema::dropIfExists('sale_refund_items');
        Schema::dropIfExists('sale_refunds');
        Schema::dropIfExists('sale_discounts');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('sale_payments');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('sale_item_modifiers');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('customers');
    }
};
