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
        Schema::create('realestate_cheque_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('realestate_invoices')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('lease_id')->nullable();
            $table->string('cheque_number');
            $table->date('cheque_date');
            $table->string('payee');
            $table->decimal('amount', 10, 2);
            $table->string('bank_name');
            $table->string('bank_account_number');
            $table->string('routing_number');
            $table->string('cheque_image')->nullable();

            $table->string('status')->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realestate_cheque_details');
    }
};
