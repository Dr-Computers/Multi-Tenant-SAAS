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
        Schema::create('realestate_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->foreignId('invoice_id')->constrained('realestate_invoices')->onDelete('cascade');
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('payment_for')->nullable();
            $table->float('amount')->default(0);
            $table->date('payment_date')->nullable();
            $table->string('receipt')->nullable();
            $table->string('receipt_number')->nullable();
            $table->integer('parent_id')->default(0);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('cheque_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->timestamps();
            
          
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realestate_payments');
    }
};
