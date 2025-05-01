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
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_account_id'); // Reference to the bank account
            $table->string('transaction_id')->unique(); // Unique transaction ID
            $table->decimal('opening_balance', 15, 2)->default(0); // Opening balance before transaction
            $table->decimal('transaction_amount', 15, 2); // Amount of the transaction
            $table->decimal('closing_balance', 15, 2); // Closing balance after transaction

            $table->enum('transaction_type', ['deposit', 'withdrawal']); // Type of transaction
            $table->date('transaction_date'); // Date of the transaction

            $table->string('reference', 100)->nullable(); // Reference number (optional)
            $table->text('description')->nullable(); // Description or notes

            $table->timestamps();

         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
