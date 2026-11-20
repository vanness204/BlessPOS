
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_shift_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->restrictOnDelete();

            $table->foreignId('shift_id')
                ->constrained('shifts')
                ->restrictOnDelete();

            $table->date('assignment_date');

            $table->string('status', 20)->default('assigned');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(
                ['employee_id', 'assignment_date'],
                'employee_shift_date_unique'
            );

            $table->index(
    ['outlet_id', 'assignment_date', 'status'],
    'esa_outlet_date_status_idx'
);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_shift_assignments');
    }
};
