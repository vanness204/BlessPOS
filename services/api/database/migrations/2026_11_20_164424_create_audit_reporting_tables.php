
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | AUDIT LOGS
        |--------------------------------------------------------------------------
        | Mencatat perubahan data penting dan tindakan sensitif.
        */
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->nullable()
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('platform_admin_id')
                ->nullable()
                ->constrained('platform_admins')
                ->nullOnDelete();

            $table->string('actor_type', 50)->nullable();
            $table->string('action', 100);
            $table->string('module', 100);

            $table->string('auditable_type', 150)->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();

            $table->string('description', 500)->nullable();

            // Snapshot perubahan sebelum dan sesudah.
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Informasi sumber aktivitas.
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_identifier', 150)->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index(
                ['tenant_id', 'created_at'],
                'audit_logs_tenant_created_index'
            );

            $table->index(
                ['outlet_id', 'created_at'],
                'audit_logs_outlet_created_index'
            );

            $table->index(
                ['user_id', 'created_at'],
                'audit_logs_user_created_index'
            );

            $table->index(
                ['platform_admin_id', 'created_at'],
                'audit_logs_platform_admin_created_index'
            );

            $table->index(
                ['module', 'action'],
                'audit_logs_module_action_index'
            );

            $table->index(
                ['auditable_type', 'auditable_id'],
                'audit_logs_auditable_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOGS
        |--------------------------------------------------------------------------
        | Mencatat aktivitas pengguna di aplikasi.
        */
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->nullable()
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('platform_admin_id')
                ->nullable()
                ->constrained('platform_admins')
                ->nullOnDelete();

            $table->string('actor_type', 50)->nullable();

            $table->string('activity_type', 100);
            $table->string('module', 100)->nullable();
            $table->string('description', 500)->nullable();

            $table->string('subject_type', 150)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_identifier', 150)->nullable();

            $table->json('properties')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index(
                ['tenant_id', 'created_at'],
                'activity_logs_tenant_created_index'
            );

            $table->index(
                ['outlet_id', 'created_at'],
                'activity_logs_outlet_created_index'
            );

            $table->index(
                ['user_id', 'created_at'],
                'activity_logs_user_created_index'
            );

            $table->index(
                ['platform_admin_id', 'created_at'],
                'activity_logs_platform_admin_created_index'
            );

            $table->index(
                ['activity_type', 'created_at'],
                'activity_logs_type_created_index'
            );

            $table->index(
                ['subject_type', 'subject_id'],
                'activity_logs_subject_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | REPORT EXPORTS
        |--------------------------------------------------------------------------
        | Menyimpan riwayat permintaan dan hasil ekspor laporan.
        */
        Schema::create('report_exports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->nullable()
                ->constrained('outlets')
                ->restrictOnDelete();

            $table->foreignId('requested_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('platform_admin_id')
                ->nullable()
                ->constrained('platform_admins')
                ->nullOnDelete();

            $table->string('export_number', 100);

            $table->string('report_type', 100);
            $table->string('report_name', 150);

            $table->enum('format', [
                'pdf',
                'xlsx',
                'csv',
            ]);

            $table->enum('status', [
                'queued',
                'processing',
                'completed',
                'failed',
                'expired',
            ])->default('queued');

            // Filter laporan yang digunakan.
            $table->json('filters')->nullable();

            // Informasi file hasil ekspor.
            $table->string('file_name', 255)->nullable();
            $table->string('file_path', 500)->nullable();
            $table->string('disk', 50)->default('local');

            $table->unsignedBigInteger('file_size')->nullable();

            $table->text('error_message')->nullable();

            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('downloaded_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'export_number'],
                'report_exports_tenant_number_unique'
            );

            $table->index(
                ['tenant_id', 'status', 'requested_at'],
                'report_exports_tenant_status_index'
            );

            $table->index(
                ['outlet_id', 'requested_at'],
                'report_exports_outlet_requested_index'
            );

            $table->index(
                ['requested_by', 'requested_at'],
                'report_exports_user_requested_index'
            );

            $table->index(
                ['platform_admin_id', 'requested_at'],
                'report_exports_platform_admin_index'
            );

            $table->index(
                ['status', 'requested_at'],
                'report_exports_status_requested_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_exports');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('audit_logs');
    }
};
