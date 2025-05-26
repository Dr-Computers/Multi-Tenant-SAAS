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
            $table->unsignedBigInteger('bank_account_id')->nullable(); // Add the cheque_id column

            // Add foreign key constraint
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('cascade');
            $table->string('vendor')->nullable();
            $table->string('reference_no')->nullable();
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
            $table->dropForeign(['bank_account_id']); // Drop the foreign key
            $table->dropColumn('bank_account_id'); // Drop the cheque_id column
            $table->dropColumn(['vendor', 'reference_no']);
        });
    }
};
