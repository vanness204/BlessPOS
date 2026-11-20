
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlet_social_media', function (Blueprint $table) {
            $table->id();

            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->cascadeOnDelete();

            $table->string('platform', 30);
            $table->string('username', 150);
            $table->string('url')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['outlet_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outlet_social_media');
    }
};
