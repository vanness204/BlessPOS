
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')
                ->after('id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->string('account_type', 20)
                ->default('member')
                ->after('password');

            $table->string('phone', 30)
                ->nullable()
                ->after('account_type');

            $table->string('status', 20)
                ->default('active')
                ->after('phone');

            $table->index(['tenant_id', 'account_type']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'account_type']);
            $table->dropConstrainedForeignId('tenant_id');
            $table->dropColumn(['account_type', 'phone', 'status']);
        });
    }
};
