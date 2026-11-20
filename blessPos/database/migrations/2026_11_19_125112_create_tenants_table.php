<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('foodcourt_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('name');
            $table->string('slug');
            $table->string('owner_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('address')->nullable();
            $table->string('logo')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['foodcourt_id', 'slug']);
            $table->index(['foodcourt_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};