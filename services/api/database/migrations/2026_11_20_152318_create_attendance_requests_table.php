
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_requests', function (Blueprint $table) {
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

            $table->foreignId('attendance_id')
                ->nullable()
                ->constrained('attendances')
                ->nullOnDelete();

            $table->string('request_type', 30);
            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->dateTime('requested_check_in_at')->nullable();
            $table->dateTime('requested_check_out_at')->nullable();

            $table->text('reason')->nullable();
            $table->string('attachment')->nullable();

            $table->string('status', 20)->default('pending');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'outlet_id', 'status']);
            $table->index(['employee_id', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_requests');
    }
};
