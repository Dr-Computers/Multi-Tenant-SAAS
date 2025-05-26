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
        Schema::create('realestate_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->integer('invoice_id')->default(0);
            $table->integer('property_id')->default(0);
            $table->integer('unit_id')->default(0);
            $table->date('invoice_month')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->integer('parent_id')->default(0);
            $table->timestamps();
            $table->string('invoice_period')->nullable();
            $table->date('invoice_period_end_date')->nullable();
            $table->string('created_in_month')->nullable();
            $table->foreignId('tenant_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('invoice_type', ['property_invoice', 'other','maintenance_invoice'])->default('property_invoice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realestate_invoices');
    }
};
