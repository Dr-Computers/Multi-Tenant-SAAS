<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realestate_leases', function (Blueprint $table) {
            $table->id();

            // Foreign keys (ensure these tables exist and use $table->id())
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            // $table->foreignId('unit_id')->constrained('property_units')->onDelete('cascade');
            $table->unsignedBigInteger('unit_id')->nullable();
            // $table->foreign('unit_id')->references('id')->on('property_units')->onDelete('cascade');

            // Lease dates
            $table->date('lease_start_date');
            $table->date('lease_end_date');
            $table->date('free_period_start')->nullable();
            $table->date('free_period_end')->nullable();

            // Pricing & Payment
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('rent_increase', 10, 2)->nullable();
            $table->decimal('security_deposit', 10, 2)->nullable();
            $table->enum('payment_frequency', ['monthly', 'quarterly', 'annually'])->default('quarterly');

            // Status & Renewal
            $table->enum('status', [
                'active',
                'renewed',
                'canceled',
                'case',
                'under review',
                'procedure_still_pending',
                'under_management',
                'awaiting_activation'
            ])->default('active');

            $table->enum('renewal_status', ['renew_applied', 'renewal_approved', 'renewal_canceled'])->nullable();
            $table->enum('renewal_option', ['yes', 'no', 'pending'])->default('pending');

            // Extra Info
            $table->timestamp('cancellation_date')->nullable();
            $table->string('property_number')->nullable();
            $table->string('contract_number')->nullable();
            $table->integer('no_of_payments')->nullable();

            $table->decimal('cheque_payment_fee', 10, 2)->nullable();
            $table->decimal('tawtheeq_fees', 10, 2)->nullable();
            $table->decimal('new_managemenmt_contract_fees', 10, 2)->nullable();

            $table->unsignedBigInteger('previous_lease_id')->nullable(); // Optional relation to same table
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            // Optional: if you want to enforce foreign key to same table
            // $table->foreign('previous_lease_id')->references('id')->on('realestate_leases')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realestate_leases');
    }
};
