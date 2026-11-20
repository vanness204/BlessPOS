
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlet_bank_accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->cascadeOnDelete();

            $table->string('bank_name', 100);
            $table->string('account_holder_name', 150);
            $table->string('account_number', 50);

            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['outlet_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outlet_bank_accounts');
    }
};
