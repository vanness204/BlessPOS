
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
        | NOTIFICATION TEMPLATES
        |--------------------------------------------------------------------------
        | Template notifikasi untuk berbagai kebutuhan aplikasi.
        */
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->string('code', 100);
            $table->string('name', 150);

            $table->string('title_template', 255);
            $table->text('body_template');

            $table->enum('channel', [
                'in_app',
                'push',
                'email',
                'sms',
            ])->default('in_app');

            $table->boolean('is_active')->default(true);

            $table->json('default_data')->nullable();

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'code', 'channel'],
                'notification_templates_tenant_code_channel_unique'
            );

            $table->index(
                ['tenant_id', 'is_active'],
                'notification_templates_tenant_active_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATION DEVICES
        |--------------------------------------------------------------------------
        | Token perangkat untuk push notification.
        */
        Schema::create('notification_devices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('platform_admin_id')
                ->nullable()
                ->constrained('platform_admins')
                ->nullOnDelete();

            $table->string('device_identifier', 150)->nullable();

            $table->string('device_name', 150)->nullable();

            $table->enum('platform', [
                'web',
                'windows',
                'android',
                'ios',
            ]);

            $table->string('provider', 50)->default('fcm');

            // Token FCM atau APNs.
            $table->string('push_token', 500)->unique();

            $table->boolean('is_active')->default(true);

            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('revoked_at')->nullable();

            $table->timestamps();

            $table->index(
                ['tenant_id', 'user_id', 'is_active'],
                'notification_devices_tenant_user_active_index'
            );

            $table->index(
                ['platform_admin_id', 'is_active'],
                'notification_devices_platform_admin_index'
            );

            $table->index(
                ['device_identifier'],
                'notification_devices_identifier_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS
        |--------------------------------------------------------------------------
        | Notifikasi yang diterima pengguna.
        */
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('outlet_id')
                ->nullable()
                ->constrained('outlets')
                ->restrictOnDelete();

            // Penerima dari akun tenant.
            $table->foreignId('recipient_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Penerima dari akun platform.
            $table->foreignId('recipient_platform_admin_id')
                ->nullable()
                ->constrained('platform_admins')
                ->nullOnDelete();

            $table->foreignId('notification_template_id')
                ->nullable()
                ->constrained('notification_templates')
                ->nullOnDelete();

            // Pengirim notifikasi.
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('created_by_platform_admin_id')
                ->nullable()
                ->constrained('platform_admins')
                ->nullOnDelete();

            $table->string('type', 100);

            $table->enum('priority', [
                'low',
                'normal',
                'high',
                'urgent',
            ])->default('normal');

            // Snapshot isi notifikasi.
            $table->string('title', 255);
            $table->text('body');

            $table->json('data')->nullable();

            $table->string('action_url', 500)->nullable();

            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            $table->index(
                ['tenant_id', 'recipient_user_id', 'created_at'],
                'notifications_tenant_user_created_index'
            );

            $table->index(
                ['recipient_platform_admin_id', 'created_at'],
                'notifications_platform_admin_created_index'
            );

            $table->index(
                ['tenant_id', 'type', 'created_at'],
                'notifications_tenant_type_created_index'
            );

            $table->index(
                ['scheduled_at', 'sent_at'],
                'notifications_schedule_sent_index'
            );

            $table->index(
                ['tenant_id', 'outlet_id'],
                'notifications_tenant_outlet_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATION DELIVERIES
        |--------------------------------------------------------------------------
        | Riwayat pengiriman notifikasi per kanal dan perangkat.
        */
        Schema::create('notification_deliveries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('notification_id')
                ->constrained('notifications')
                ->restrictOnDelete();

            $table->foreignId('notification_device_id')
                ->nullable()
                ->constrained('notification_devices')
                ->nullOnDelete();

            $table->enum('channel', [
                'in_app',
                'push',
                'email',
                'sms',
            ]);

            $table->enum('status', [
                'queued',
                'processing',
                'sent',
                'delivered',
                'failed',
            ])->default('queued');

            $table->string('provider', 50)->nullable();
            $table->string('provider_message_id', 255)->nullable();

            $table->unsignedSmallInteger('attempt_count')->default(0);
            $table->unsignedSmallInteger('max_attempts')->default(3);

            $table->text('error_message')->nullable();

            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('next_retry_at')->nullable();

            $table->timestamps();

            $table->index(
                ['notification_id', 'channel'],
                'notification_deliveries_notification_channel_index'
            );

            $table->index(
                ['status', 'next_retry_at'],
                'notification_deliveries_retry_index'
            );

            $table->index(
                ['notification_device_id', 'status'],
                'notification_deliveries_device_status_index'
            );

            $table->index(
                ['provider', 'provider_message_id'],
                'notification_deliveries_provider_message_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_deliveries');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_devices');
        Schema::dropIfExists('notification_templates');
    }
};
