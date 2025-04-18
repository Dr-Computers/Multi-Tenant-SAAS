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
        Schema::create('realestate_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->default(0);
            $table->foreign('invoice_id')->references('id')->on('realestate_invoices')->onDelete('cascade');
    
            $table->integer('invoice_type')->default(0);
            $table->float('amount', 10, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->float('tax_amount', 10, 2)->default(0.00);
            $table->float('grand_amount', 10, 2)->default(0.00);
            $table->enum('vat_inclusion', ['included', 'excluded'])->default('excluded');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realestate_invoice_items');
    }
};
