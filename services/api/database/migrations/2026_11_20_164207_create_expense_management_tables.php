
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
        | EXPENSE CATEGORIES
        |--------------------------------------------------------------------------
        | Kategori pengeluaran operasional setiap tenant.
        */
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->string('code', 50);
            $table->string('name', 100);
            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'code'],
                'expense_categories_tenant_code_unique'
            );

            $table->index(
                ['tenant_id', 'is_active'],
                'expense_categories_tenant_active_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | EXPENSES
        |--------------------------------------------------------------------------
        | Transaksi pengeluaran operasional.
        */
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->nullable()
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->foreignId('expense_category_id')
                ->nullable()
                ->constrained('expense_categories')
                ->restrictOnDelete();

            $table->foreignId('payment_method_id')
                ->nullable()
                ->constrained('payment_methods')
                ->restrictOnDelete();

            // Diisi jika pengeluaran dibayar menggunakan kasir.
            $table->foreignId('cash_session_id')
                ->nullable()
                ->constrained('cash_sessions')
                ->restrictOnDelete();

            // Referensi pergerakan kas keluar jika dibayar tunai.
            $table->foreignId('cash_movement_id')
                ->nullable()
                ->constrained('cash_movements')
                ->restrictOnDelete();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('paid_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('expense_number', 100);

            // Snapshot kategori untuk menjaga riwayat.
            $table->string('category_name', 100)->nullable();

            $table->string('title', 150);
            $table->text('description')->nullable();

            $table->date('expense_date');

            $table->enum('status', [
                'draft',
                'pending',
                'approved',
                'paid',
                'rejected',
                'cancelled',
            ])->default('draft');

            // Informasi pembayaran.
            $table->enum('payment_status', [
                'unpaid',
                'paid',
            ])->default('unpaid');

            $table->char('currency', 3)->default('IDR');

            $table->decimal('subtotal', 18, 2)->default(0);
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2)->default(0);

            $table->string('reference_number', 100)->nullable();

            $table->dateTime('approved_at')->nullable();
            $table->dateTime('paid_at')->nullable();

            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'expense_number'],
                'expenses_tenant_number_unique'
            );

            $table->index(
                ['tenant_id', 'outlet_id', 'expense_date'],
                'expenses_tenant_outlet_date_index'
            );

            $table->index(
                ['tenant_id', 'status', 'expense_date'],
                'expenses_tenant_status_date_index'
            );

            $table->index(
                ['tenant_id', 'payment_status'],
                'expenses_tenant_payment_status_index'
            );

            $table->index(
                ['expense_category_id', 'expense_date'],
                'expenses_category_date_index'
            );

            $table->index(
                ['cash_session_id', 'status'],
                'expenses_cash_session_status_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | EXPENSE ATTACHMENTS
        |--------------------------------------------------------------------------
        | Foto nota, invoice, dan bukti pembayaran.
        */
        Schema::create('expense_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('expense_id')
                ->constrained('expenses')
                ->restrictOnDelete();

            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->string('disk', 50)->default('public');

            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->default(0);

            $table->string('description')->nullable();

            $table->timestamps();

            $table->index(
                ['expense_id', 'created_at'],
                'expense_attachments_expense_created_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_attachments');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
    }
};
