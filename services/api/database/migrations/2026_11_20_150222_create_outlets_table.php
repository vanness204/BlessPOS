
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();

            // Identitas tenant dan outlet
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->string('name');
            $table->string('code', 50);
            $table->string('type', 50)->nullable();

            // Gudang dan kategori
            $table->unsignedBigInteger('default_warehouse_id')->nullable();
            $table->string('category')->nullable();

            // Kontak
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();

            // Mata uang dan gambar
            $table->string('currency', 3)->default('IDR');
            $table->string('logo')->nullable();

            // Alamat dan lokasi
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Zona waktu
            $table->string('timezone', 50)->default('Asia/Jakarta');

            // Status outlet
            $table->string('status', 20)->default('active');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};
