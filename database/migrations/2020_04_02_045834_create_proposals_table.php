<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'proposals', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('proposal_id');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->date('issue_date');
            $table->date('send_date')->nullable();
            $table->integer('category_id');
            $table->integer('status')->default('0');
            $table->integer('discount_apply')->default('0');
            $table->integer('is_convert')->default('0');
            $table->integer('converted_invoice_id')->default('0');
            $table->integer('converted_retainer_id')->default('0');
            $table->integer('created_by')->default('0');
            $table->timestamps();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposals');
    }
}
