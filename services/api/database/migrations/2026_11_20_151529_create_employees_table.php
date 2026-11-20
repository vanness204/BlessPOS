
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->string('employee_code', 50);
            $table->string('name', 150);
            $table->string('job_title', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();

            // PIN disimpan dalam bentuk hash, bukan teks asli.
            $table->string('pin_hash')->nullable();

            $table->date('join_date')->nullable();
            $table->date('resign_date')->nullable();

            $table->string('status', 20)->default('active');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['outlet_id', 'employee_code']);
            $table->index(['tenant_id', 'outlet_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
