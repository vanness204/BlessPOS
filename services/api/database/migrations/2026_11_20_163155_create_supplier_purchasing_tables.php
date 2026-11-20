
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. SUPPLIERS
        |--------------------------------------------------------------------------
        */

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->string('code', 50)->nullable();
            $table->string('name', 150);
            $table->string('contact_person', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->text('address')->nullable();
            $table->string('tax_number', 50)->nullable();
            $table->text('notes')->nullable();

            $table->decimal('opening_balance', 18, 2)->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['tenant_id', 'code'],
                'sup_tenant_code_unique'
            );

            $table->index(
                ['tenant_id', 'name'],
                'sup_tenant_name_idx'
            );

            $table->index(
                ['tenant_id', 'is_active'],
                'sup_tenant_active_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 2. PURCHASES
        |--------------------------------------------------------------------------
        | Header transaksi pembelian.
        */

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('supplier_id')
                ->constrained('suppliers')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->nullable()
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->restrictOnDelete();

            $table->string('purchase_number', 100);
            $table->string('supplier_invoice_number', 100)->nullable();

            $table->date('purchase_date');
            $table->date('expected_date')->nullable();

            $table->string('status', 30)->default('draft')
                ->comment('draft, ordered, partially_received, received, cancelled');

            $table->string('payment_status', 20)->default('unpaid')
                ->comment('unpaid, partial, paid');

            $table->string('payment_term', 20)->default('cash')
                ->comment('cash atau credit');

            $table->date('due_date')->nullable();

            $table->decimal('subtotal', 18, 2)->default(0);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('shipping_cost', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->decimal('paid_amount', 18, 2)->default(0);

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'purchase_number'],
                'pur_tenant_number_unique'
            );

            $table->index(
                ['tenant_id', 'supplier_id', 'purchase_date'],
                'pur_tenant_supplier_date_idx'
            );

            $table->index(
                ['tenant_id', 'status', 'purchase_date'],
                'pur_tenant_status_date_idx'
            );

            $table->index(
                ['warehouse_id', 'status'],
                'pur_warehouse_status_idx'
            );

            $table->index(
                ['tenant_id', 'payment_status', 'due_date'],
                'pur_tenant_payment_due_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 3. PURCHASE ITEMS
        |--------------------------------------------------------------------------
        */

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_id')
                ->constrained('purchases')
                ->cascadeOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            $table->decimal('quantity_ordered', 18, 4)->default(0);
            $table->decimal('quantity_received', 18, 4)->default(0);
            $table->decimal('unit_cost', 18, 4)->default(0);
            $table->decimal('discount_amount', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('subtotal', 18, 2)->default(0);
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['purchase_id', 'product_variant_id'],
                'pi_purchase_variant_unique'
            );

            $table->index(
                'product_variant_id',
                'pi_variant_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 4. PURCHASE RECEIPTS
        |--------------------------------------------------------------------------
        | Barang dapat diterima secara bertahap.
        */

        Schema::create('purchase_receipts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('purchase_id')
                ->constrained('purchases')
                ->restrictOnDelete();

            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->restrictOnDelete();

            $table->string('receipt_number', 100);

            $table->string('status', 20)->default('draft')
                ->comment('draft, posted, cancelled');

            $table->timestamp('received_at')->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('received_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('posted_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'receipt_number'],
                'pr_tenant_number_unique'
            );

            $table->index(
                ['tenant_id', 'purchase_id', 'status'],
                'pr_tenant_purchase_status_idx'
            );

            $table->index(
                ['warehouse_id', 'received_at'],
                'pr_warehouse_received_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 5. PURCHASE RECEIPT ITEMS
        |--------------------------------------------------------------------------
        */

        Schema::create('purchase_receipt_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_receipt_id')
                ->constrained('purchase_receipts')
                ->cascadeOnDelete();

            $table->foreignId('purchase_item_id')
                ->constrained('purchase_items')
                ->restrictOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            $table->decimal('quantity_received', 18, 4)->default(0);
            $table->decimal('unit_cost', 18, 4)->default(0);
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(
                ['purchase_item_id', 'product_variant_id'],
                'pri_purchase_item_variant_idx'
            );

            $table->index(
                'product_variant_id',
                'pri_variant_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 6. PURCHASE PAYMENTS
        |--------------------------------------------------------------------------
        | Mendukung pembayaran penuh dan cicilan.
        */

        Schema::create('purchase_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('purchase_id')
                ->constrained('purchases')
                ->restrictOnDelete();

            $table->string('payment_number', 100);
            $table->string('payment_method', 50)
                ->comment('cash, bank_transfer, qris, other');

            $table->decimal('amount', 18, 2);
            $table->timestamp('paid_at');

            $table->string('reference_number', 150)->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'payment_number'],
                'pp_tenant_number_unique'
            );

            $table->index(
                ['tenant_id', 'purchase_id', 'paid_at'],
                'pp_tenant_purchase_paid_idx'
            );

            $table->index(
                ['tenant_id', 'payment_method', 'paid_at'],
                'pp_tenant_method_paid_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_payments');
        Schema::dropIfExists('purchase_receipt_items');
        Schema::dropIfExists('purchase_receipts');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('suppliers');
    }
};
