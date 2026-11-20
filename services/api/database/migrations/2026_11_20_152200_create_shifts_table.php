
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->string('name', 100);
            $table->string('code', 30);

            $table->time('start_time');
            $table->time('end_time');

            $table->unsignedSmallInteger('break_duration_minutes')->default(0);
            $table->unsignedSmallInteger('late_tolerance_minutes')->default(0);

            $table->boolean('crosses_midnight')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['outlet_id', 'code']);
            $table->index(['tenant_id', 'outlet_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
