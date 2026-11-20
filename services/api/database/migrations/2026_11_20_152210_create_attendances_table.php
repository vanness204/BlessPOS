
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
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
                ->nullable()
                ->constrained('shifts')
                ->nullOnDelete();

            $table->date('attendance_date');

            $table->dateTime('check_in_at')->nullable();
            $table->dateTime('check_out_at')->nullable();

            $table->unsignedInteger('work_duration_minutes')->default(0);

            $table->string('status', 30)->default('present');

            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();

            $table->string('check_in_photo')->nullable();
            $table->string('check_out_photo')->nullable();

            $table->text('notes')->nullable();

            $table->foreignId('corrected_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('corrected_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['employee_id', 'attendance_date'],
                'attendance_employee_date_unique'
            );

            $table->index(['tenant_id', 'outlet_id', 'attendance_date']);
            $table->index(['outlet_id', 'status', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
