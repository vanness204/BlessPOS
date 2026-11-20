
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
        | Subscription Plans
        |--------------------------------------------------------------------------
        */

        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();

            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();

            $table->unsignedBigInteger('price');
            $table->char('currency', 3)->default('IDR');

            $table->string('billing_interval', 20)->default('yearly');
            $table->unsignedSmallInteger('billing_interval_count')->default(1);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['is_active', 'billing_interval']);
        });

        /*
        |--------------------------------------------------------------------------
        | Tenant Subscriptions
        |--------------------------------------------------------------------------
        */

        Schema::create('tenant_subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('subscription_plan_id')
                ->constrained('subscription_plans')
                ->restrictOnDelete();

            // Snapshot harga saat tenant berlangganan.
            $table->unsignedBigInteger('price');
            $table->char('currency', 3)->default('IDR');

            $table->string('status', 30)->default('pending');

            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->dateTime('next_billing_at')->nullable();

            $table->boolean('auto_renew')->default(false);

            $table->dateTime('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['status', 'ends_at']);
            $table->index('next_billing_at');
        });

        /*
        |--------------------------------------------------------------------------
        | Subscription Payments
        |--------------------------------------------------------------------------
        */

        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('tenant_subscription_id')
                ->constrained('tenant_subscriptions')
                ->restrictOnDelete();

            $table->string('payment_number', 100)->unique();

            $table->unsignedBigInteger('amount');
            $table->char('currency', 3)->default('IDR');

            $table->string('payment_method', 30);
            // transfer_bank atau qris

            $table->string('status', 30)->default('pending');
            // pending, verified, rejected, refunded

            $table->string('bank_name')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('reference_number')->nullable();

            $table->string('proof_path')->nullable();
            $table->string('proof_disk')->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('platform_admins')
                ->nullOnDelete();

            $table->dateTime('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->dateTime('paid_at')->nullable();
            $table->dateTime('refunded_at')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_subscription_id', 'status']);
            $table->index('paid_at');
        });

        /*
        |--------------------------------------------------------------------------
        | Subscription Invoices
        |--------------------------------------------------------------------------
        */

        Schema::create('subscription_invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->foreignId('tenant_subscription_id')
                ->constrained('tenant_subscriptions')
                ->restrictOnDelete();

            $table->foreignId('subscription_payment_id')
                ->nullable()
                ->constrained('subscription_payments')
                ->nullOnDelete();

            $table->string('invoice_number', 100)->unique();

            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('tax_amount')->default(0);
            $table->unsignedBigInteger('total_amount');

            $table->char('currency', 3)->default('IDR');

            $table->string('status', 30)->default('issued');
            // issued, paid, overdue, void

            $table->dateTime('issued_at');
            $table->dateTime('due_at')->nullable();
            $table->dateTime('paid_at')->nullable();

            $table->json('billing_snapshot')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['status', 'due_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_invoices');
        Schema::dropIfExists('subscription_payments');
        Schema::dropIfExists('tenant_subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
