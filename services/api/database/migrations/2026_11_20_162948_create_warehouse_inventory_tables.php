
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
        | 1. WAREHOUSES
        |--------------------------------------------------------------------------
        | Menampung gudang fisik dan lokasi stok langsung milik outlet.
        */

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->foreignId('outlet_id')->nullable()->constrained('outlets')->restrictOnDelete();

            $table->string('code', 50);
            $table->string('name', 150);
            $table->string('warehouse_type', 30)->default('warehouse')
                ->comment('warehouse atau outlet_stock');

            $table->text('address')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code'], 'wh_tenant_code_unique');
            $table->index(['tenant_id', 'outlet_id', 'is_active'], 'wh_tenant_outlet_idx');
            $table->index(['tenant_id', 'warehouse_type'], 'wh_tenant_type_idx');
        });

        /*
        |--------------------------------------------------------------------------
        | 2. OUTLET INVENTORY SETTINGS
        |--------------------------------------------------------------------------
        | Pengaturan inventaris untuk setiap outlet.
        */

        Schema::create('outlet_inventory_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->foreignId('outlet_id')->constrained('outlets')->restrictOnDelete();

            $table->foreignId('default_warehouse_id')
                ->nullable()
                ->constrained('warehouses')
                ->nullOnDelete();

            $table->boolean('stock_tracking_enabled')->default(true);
            $table->boolean('allow_negative_stock')->default(false);
            $table->boolean('low_stock_alert_enabled')->default(true);

            $table->string('costing_method', 30)->default('weighted_average')
                ->comment('weighted_average atau fifo');

            $table->timestamps();

            $table->unique('outlet_id', 'ois_outlet_unique');
            $table->index(['tenant_id', 'stock_tracking_enabled'], 'ois_tenant_tracking_idx');
        });

        /*
        |--------------------------------------------------------------------------
        | 3. STOCK ADJUSTMENTS
        |--------------------------------------------------------------------------
        | Dokumen penyesuaian stok.
        */

        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->restrictOnDelete();

            $table->string('adjustment_number', 100);
            $table->string('adjustment_type', 30)->default('count')
                ->comment('count, increase, decrease');

            $table->string('status', 20)->default('draft')
                ->comment('draft, posted, cancelled');

            $table->string('reason', 255)->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('posted_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'adjustment_number'],
                'sa_tenant_number_unique'
            );

            $table->index(['tenant_id', 'warehouse_id', 'status'], 'sa_tenant_wh_status_idx');
            $table->index(['tenant_id', 'created_at'], 'sa_tenant_created_idx');
        });

        /*
        |--------------------------------------------------------------------------
        | 4. STOCK ADJUSTMENT ITEMS
        |--------------------------------------------------------------------------
        | Detail produk dan perubahan jumlah stok.
        */

        Schema::create('stock_adjustment_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stock_adjustment_id')
                ->constrained('stock_adjustments')
                ->cascadeOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            $table->decimal('system_quantity', 18, 4)->default(0);
            $table->decimal('counted_quantity', 18, 4)->default(0);
            $table->decimal('difference_quantity', 18, 4)->default(0);
            $table->decimal('unit_cost', 18, 4)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['stock_adjustment_id', 'product_variant_id'],
                'sai_adjustment_variant_unique'
            );

            $table->index('product_variant_id', 'sai_variant_idx');
        });

        /*
        |--------------------------------------------------------------------------
        | 5. STOCK TRANSFERS
        |--------------------------------------------------------------------------
        | Dokumen perpindahan stok antar lokasi.
        */

        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();

            $table->foreignId('from_warehouse_id')
                ->constrained('warehouses')
                ->restrictOnDelete();

            $table->foreignId('to_warehouse_id')
                ->constrained('warehouses')
                ->restrictOnDelete();

            $table->string('transfer_number', 100);

            $table->string('status', 20)->default('draft')
                ->comment('draft, approved, in_transit, received, cancelled');

            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('shipped_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('received_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'transfer_number'],
                'st_tenant_number_unique'
            );

            $table->index(['tenant_id', 'status', 'created_at'], 'st_tenant_status_idx');
            $table->index(['from_warehouse_id', 'status'], 'st_from_status_idx');
            $table->index(['to_warehouse_id', 'status'], 'st_to_status_idx');
        });

        /*
        |--------------------------------------------------------------------------
        | 6. STOCK TRANSFER ITEMS
        |--------------------------------------------------------------------------
        | Detail produk yang dipindahkan.
        */

        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stock_transfer_id')
                ->constrained('stock_transfers')
                ->cascadeOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            $table->decimal('quantity_requested', 18, 4)->default(0);
            $table->decimal('quantity_sent', 18, 4)->default(0);
            $table->decimal('quantity_received', 18, 4)->default(0);
            $table->decimal('unit_cost', 18, 4)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['stock_transfer_id', 'product_variant_id'],
                'sti_transfer_variant_unique'
            );

            $table->index('product_variant_id', 'sti_variant_idx');
        });

        /*
        |--------------------------------------------------------------------------
        | 7. STOCK BALANCES
        |--------------------------------------------------------------------------
        | Saldo terkini setiap varian pada setiap lokasi.
        */

        Schema::create('stock_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();

            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->restrictOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            $table->decimal('quantity_on_hand', 18, 4)->default(0);
            $table->decimal('quantity_reserved', 18, 4)->default(0);
            $table->decimal('average_cost', 18, 4)->default(0);

            $table->timestamps();

            $table->unique(
                ['warehouse_id', 'product_variant_id'],
                'sb_warehouse_variant_unique'
            );

            $table->index(
                ['tenant_id', 'product_variant_id'],
                'sb_tenant_variant_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 8. STOCK MOVEMENTS
        |--------------------------------------------------------------------------
        | Riwayat setiap perubahan stok.
        | quantity_change positif = stok masuk.
        | quantity_change negatif = stok keluar.
        */

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->restrictOnDelete();

            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->restrictOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            $table->foreignId('stock_adjustment_item_id')
                ->nullable()
                ->constrained('stock_adjustment_items')
                ->restrictOnDelete();

            $table->foreignId('stock_transfer_item_id')
                ->nullable()
                ->constrained('stock_transfer_items')
                ->restrictOnDelete();

            $table->string('movement_type', 30)
                ->comment('opening, purchase, sale, adjustment, transfer, return');

            $table->decimal('quantity_change', 18, 4);
            $table->decimal('quantity_before', 18, 4)->default(0);
            $table->decimal('quantity_after', 18, 4)->default(0);
            $table->decimal('unit_cost', 18, 4)->default(0);

            $table->string('reference_number', 100)->nullable();
            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(
                ['tenant_id', 'warehouse_id', 'product_variant_id', 'created_at'],
                'sm_tenant_wh_variant_date_idx'
            );

            $table->index(
                ['movement_type', 'created_at'],
                'sm_type_date_idx'
            );

            $table->index('stock_adjustment_item_id', 'sm_adjustment_item_idx');
            $table->index('stock_transfer_item_id', 'sm_transfer_item_idx');
            $table->index('reference_number', 'sm_reference_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('stock_balances');
        Schema::dropIfExists('stock_transfer_items');
        Schema::dropIfExists('stock_transfers');
        Schema::dropIfExists('stock_adjustment_items');
        Schema::dropIfExists('stock_adjustments');
        Schema::dropIfExists('outlet_inventory_settings');
        Schema::dropIfExists('warehouses');
    }
};
