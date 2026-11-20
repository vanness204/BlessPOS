
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->string('name');
            $table->string('slug', 100);
            $table->text('description')->nullable();

            // Role bawaan sistem tidak dapat dihapus sembarangan.
            $table->boolean('is_system')->default(false);

            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'is_system']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
