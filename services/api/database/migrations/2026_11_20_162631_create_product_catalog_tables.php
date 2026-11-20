
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
        | 1. Categories
        |--------------------------------------------------------------------------
        */

        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->string('name', 150);
            $table->string('slug', 180);
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['tenant_id', 'slug'],
                'categories_tenant_slug_unique'
            );

            $table->index(
                ['tenant_id', 'parent_id', 'is_active'],
                'categories_tenant_parent_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 2. Units
        |--------------------------------------------------------------------------
        | Master satuan global, misalnya pcs, kg, gram, liter, ml.
        */

        Schema::create('units', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);
            $table->string('code', 30)->unique();
            $table->string('symbol', 20);
            $table->string('unit_type', 30)->default('quantity');
            $table->unsignedTinyInteger('decimal_places')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | 3. Products
        |--------------------------------------------------------------------------
        */

        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->foreignId('base_unit_id')
                ->nullable()
                ->constrained('units')
                ->nullOnDelete();

            $table->string('code', 100)->nullable();
            $table->string('name', 180);
            $table->text('description')->nullable();

            // goods, service, combo
            $table->string('product_type', 30)->default('goods');

            $table->string('image_path')->nullable();

            $table->boolean('track_stock')->default(true);
            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['tenant_id', 'id'],
                'products_tenant_id_unique'
            );

            $table->unique(
                ['tenant_id', 'code'],
                'products_tenant_code_unique'
            );

            $table->index(
                ['tenant_id', 'category_id', 'is_active'],
                'products_tenant_category_idx'
            );

            $table->index(
                ['tenant_id', 'product_type', 'is_active'],
                'products_tenant_type_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 4. Product Variants
        |--------------------------------------------------------------------------
        */

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->string('name', 150);
            $table->string('sku', 100)->nullable();
            $table->string('barcode', 100)->nullable();

            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            $table->decimal('weight', 18, 4)->nullable();
            $table->string('weight_unit', 20)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['tenant_id', 'id'],
                'variants_tenant_id_unique'
            );

            $table->unique(
                ['tenant_id', 'sku'],
                'variants_tenant_sku_unique'
            );

            $table->unique(
                ['tenant_id', 'barcode'],
                'variants_tenant_barcode_unique'
            );

            $table->index(
                ['tenant_id', 'product_id', 'is_active'],
                'variants_tenant_product_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 5. Product Outlets
        |--------------------------------------------------------------------------
        | Menentukan produk mana yang tersedia pada setiap outlet.
        */

        Schema::create('product_outlets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->string('outlet_sku', 100)->nullable();
            $table->boolean('is_available')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(
                ['outlet_id', 'product_id'],
                'product_outlets_outlet_product_unique'
            );

            $table->unique(
                ['outlet_id', 'outlet_sku'],
                'product_outlets_outlet_sku_unique'
            );

            $table->index(
                ['tenant_id', 'outlet_id', 'is_available'],
                'product_outlets_tenant_available_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 6. Outlet Product Prices
        |--------------------------------------------------------------------------
        | Harga varian produk dapat berbeda antar-outlet.
        */

        Schema::create('outlet_product_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            $table->decimal('selling_price', 18, 2);
            $table->decimal('cost_price', 18, 2)->nullable();
            $table->decimal('compare_at_price', 18, 2)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['outlet_id', 'product_variant_id'],
                'outlet_prices_outlet_variant_unique'
            );

            $table->index(
                ['tenant_id', 'outlet_id', 'is_active'],
                'outlet_prices_tenant_active_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 7. Product Units
        |--------------------------------------------------------------------------
        | Konversi satuan pembelian dan penjualan.
        */

        Schema::create('product_units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('unit_id')
                ->constrained('units')
                ->restrictOnDelete();

            // Contoh: 1 dus = 12 pcs
            $table->decimal('conversion_factor', 18, 6);

            $table->boolean('is_purchase_unit')->default(false);
            $table->boolean('is_sale_unit')->default(false);

            $table->timestamps();

            $table->unique(
                ['product_id', 'unit_id'],
                'product_units_product_unit_unique'
            );

            $table->index(
                ['tenant_id', 'product_id'],
                'product_units_tenant_product_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 8. Modifier Groups
        |--------------------------------------------------------------------------
        | Contoh: Pilihan ukuran, topping, level gula.
        */

        Schema::create('modifier_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->string('name', 150);

            // single, multiple
            $table->string('selection_type', 20)->default('single');

            $table->unsignedSmallInteger('min_selection')->default(0);
            $table->unsignedSmallInteger('max_selection')->nullable();

            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'id'],
                'modifier_groups_tenant_id_unique'
            );

            $table->index(
                ['tenant_id', 'is_active'],
                'modifier_groups_tenant_active_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 9. Modifier Options
        |--------------------------------------------------------------------------
        | Contoh: Extra shot, less sugar, topping keju.
        */

        Schema::create('modifier_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('modifier_group_id')
                ->constrained('modifier_groups')
                ->cascadeOnDelete();

            $table->string('name', 150);
            $table->decimal('price_adjustment', 18, 2)->default(0);

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(
                ['tenant_id', 'modifier_group_id', 'is_active'],
                'modifier_options_group_active_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 10. Product Modifier Groups
        |--------------------------------------------------------------------------
        | Menghubungkan produk dengan grup modifier.
        */

        Schema::create('product_modifier_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('modifier_group_id')
                ->constrained('modifier_groups')
                ->cascadeOnDelete();

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(
                ['product_id', 'modifier_group_id'],
                'product_modifier_unique'
            );

            $table->index(
                ['tenant_id', 'product_id'],
                'product_modifier_tenant_idx'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | 11. Product Components
        |--------------------------------------------------------------------------
        | Komponen produk combo/paket.
        | Contoh: Paket Hemat berisi Burger + Kentang + Minuman.
        */

        Schema::create('product_components', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('parent_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            $table->foreignId('component_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            $table->decimal('quantity', 18, 4);

            $table->timestamps();

            $table->unique(
                ['parent_variant_id', 'component_variant_id'],
                'product_components_unique'
            );

            $table->index(
                ['tenant_id', 'parent_variant_id'],
                'product_components_parent_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_components');
        Schema::dropIfExists('product_modifier_groups');
        Schema::dropIfExists('modifier_options');
        Schema::dropIfExists('modifier_groups');
        Schema::dropIfExists('product_units');
        Schema::dropIfExists('outlet_product_prices');
        Schema::dropIfExists('product_outlets');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('units');
        Schema::dropIfExists('categories');
    }
};
