<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('realestate_leases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id'); // FK to tenants
            $table->string('property');
            $table->string('unit');
            $table->date('lease_start_date');
            $table->date('lease_end_date');
            $table->date('free_period_start')->nullable();
            $table->date('free_period_end')->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->enum('status', [
                'active', 'renewed', 'canceled', 'case',
                'under review', 'procedure_still_pending',
                'under_management', 'awaiting_activation'
            ])->default('active');
            $table->enum('renewal_status', ['renew_applied', 'renewal_approved', 'renewal_canceled'])->nullable();

            $table->unsignedBigInteger('previous_lease_id')->nullable();
            $table->enum('renewal_option', ['yes', 'no', 'pending'])->default('pending');
            $table->decimal('rent_increase', 10, 2)->nullable();
            $table->decimal('security_deposit', 10, 2)->nullable();
            $table->enum('payment_frequency', ['monthly', 'quarterly', 'annually'])->default('quarterly');
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();

            $table->timestamp('cancellation_date')->nullable();
            $table->string('property_number')->nullable();
            $table->string('contract_number')->nullable();
            $table->integer('no_of_payments')->nullable();

            $table->decimal('cheque_payment_fee', 10, 2)->nullable();
            $table->decimal('tawtheeq_fees', 10, 2)->nullable();
            $table->decimal('new_managemenmt_contract_fees', 10, 2)->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('previous_lease_id')->references('id')->on('realestate_leases')->onDelete('set null');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('set null');
            $table->foreign('unit_id')->references('id')->on('property_units')->onDelete('set null');
     
           
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realestate_leases');
    }
};
