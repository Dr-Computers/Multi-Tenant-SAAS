<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            
            $table->decimal('base_amount', 10, 2)->default(0.00)->after('amount');
            // Add VAT amount column
            $table->decimal('vat_amount', 10, 2)->default(0.00)->after('base_amount');
            // Add VAT included column to indicate if total includes VAT
            $table->string('vat_included')->after('vat_amount');
            $table->unsignedBigInteger('liability_id')->nullable()->after('expense_type'); // Change the position if necessary

            // Optional: Add foreign key constraint
            $table->foreign('liability_id')->references('id')->on('liabilities')->onDelete('set null');


       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['base_amount', 'vat_amount', 'vat_included']);
            $table->dropForeign(['liability_id']); // Drop foreign key first
            $table->dropColumn('liability_id');
        });
    }
};
