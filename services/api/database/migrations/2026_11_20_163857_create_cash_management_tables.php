
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
        | CASH REGISTERS
        |--------------------------------------------------------------------------
        | Daftar mesin/loket kasir pada setiap outlet.
        */
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->string('code', 50);
            $table->string('name', 100);
            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'outlet_id', 'code'],
                'cash_registers_tenant_outlet_code_unique'
            );

            $table->index(
                ['tenant_id', 'outlet_id', 'is_active'],
                'cash_registers_tenant_outlet_active_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | CASH SESSIONS
        |--------------------------------------------------------------------------
        | Sesi buka dan tutup kasir.
        */
        Schema::create('cash_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->foreignId('cash_register_id')
                ->constrained('cash_registers')
                ->restrictOnDelete();

            $table->foreignId('opened_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('closed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('session_number', 100);

            $table->enum('status', [
                'open',
                'closed',
                'cancelled',
            ])->default('open');

            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();

            // Uang modal awal ketika kasir dibuka.
            $table->decimal('opening_cash', 18, 2)->default(0);

            // Total kas yang seharusnya tersedia ketika penutupan.
            $table->decimal('expected_cash', 18, 2)->nullable();

            // Total uang tunai hasil penghitungan fisik.
            $table->decimal('counted_cash', 18, 2)->nullable();

            // Selisih uang fisik dengan kas yang diharapkan.
            $table->decimal('cash_difference', 18, 2)->nullable();

            $table->text('opening_notes')->nullable();
            $table->text('closing_notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'session_number'],
                'cash_sessions_tenant_number_unique'
            );

            $table->index(
                ['cash_register_id', 'status'],
                'cash_sessions_register_status_index'
            );

            $table->index(
                ['tenant_id', 'outlet_id', 'opened_at'],
                'cash_sessions_tenant_outlet_opened_index'
            );

            $table->index(
                ['opened_by', 'status'],
                'cash_sessions_opened_by_status_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | CASH MOVEMENTS
        |--------------------------------------------------------------------------
        | Pencatatan kas masuk dan keluar di luar transaksi penjualan.
        */
        Schema::create('cash_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->foreignId('cash_session_id')
                ->constrained('cash_sessions')
                ->restrictOnDelete();

            $table->foreignId('recorded_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('movement_number', 100);

            $table->enum('type', [
                'in',
                'out',
            ]);

            $table->string('category', 100);

            $table->decimal('amount', 18, 2);

            $table->string('description')->nullable();
            $table->string('reference_number', 100)->nullable();

            $table->dateTime('occurred_at');

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'movement_number'],
                'cash_movements_tenant_number_unique'
            );

            $table->index(
                ['cash_session_id', 'occurred_at'],
                'cash_movements_session_occurred_index'
            );

            $table->index(
                ['tenant_id', 'outlet_id', 'type'],
                'cash_movements_tenant_outlet_type_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_movements');
        Schema::dropIfExists('cash_sessions');
        Schema::dropIfExists('cash_registers');
    }
};
