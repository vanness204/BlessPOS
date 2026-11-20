
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlet_report_recipients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->cascadeOnDelete();

            $table->string('report_type', 30);
            $table->string('email', 255);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique([
                'outlet_id',
                'report_type',
                'email',
            ], 'outlet_report_recipient_unique');

            $table->index([
                'outlet_id',
                'report_type',
                'is_active',
            ], 'outlet_report_recipient_status_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outlet_report_recipients');
    }
};
