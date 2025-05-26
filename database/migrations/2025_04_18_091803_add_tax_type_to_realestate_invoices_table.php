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
        Schema::table('realestate_invoices', function (Blueprint $table) {
            $table->string('tax_type')->nullable();
            $table->decimal('sub_total',8,2)->nullable();
            $table->decimal('total_tax',8,2)->nullable();
            $table->string('discount_reason',8,2)->nullable();
            $table->decimal('discount_amount',8,2)->nullable();
            $table->decimal('grand_total',8,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realestate_invoices', function (Blueprint $table) {
            $table->dropColumn('tax_type');
        });
    }
};
