
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')
                    ->nullable()
                    ->constrained('roles')
                    ->restrictOnDelete();
            }

            if (!Schema::hasColumn('users', 'foodcourt_id')) {
                $table->foreignId('foodcourt_id')
                    ->nullable()
                    ->constrained('foodcourts')
                    ->restrictOnDelete();
            }

            if (!Schema::hasColumn('users', 'tenant_id')) {
                $table->foreignId('tenant_id')
                    ->nullable()
                    ->constrained('tenants')
                    ->restrictOnDelete();
            }

            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }

            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }

            if (
                Schema::hasColumn('users', 'foodcourt_id') &&
                Schema::hasColumn('users', 'tenant_id')
            ) {
                $table->index(
                    ['foodcourt_id', 'tenant_id'],
                    'users_foodcourt_tenant_index'
                );
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Jangan menghapus kolom yang mungkin sudah ada
            // sebelum migration ini dijalankan.
            if (Schema::hasColumn('users', 'foodcourt_id') &&
                Schema::hasColumn('users', 'tenant_id')) {
                $table->dropIndex('users_foodcourt_tenant_index');
            }

            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropConstrainedForeignId('role_id');
            }

            if (Schema::hasColumn('users', 'foodcourt_id')) {
                $table->dropConstrainedForeignId('foodcourt_id');
            }

            if (Schema::hasColumn('users', 'tenant_id')) {
                $table->dropConstrainedForeignId('tenant_id');
            }

            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('users', 'last_login_at')) {
                $table->dropColumn('last_login_at');
            }
        });
    }
};
