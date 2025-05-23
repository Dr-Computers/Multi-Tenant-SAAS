<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->integer('user_id');
            $table->string('user_type');
            $table->integer('account');
            $table->string('type');
            $table->decimal('amount', 15, 2)->default('0.0');
            $table->text('description')->nullable();
            $table->date('date');
            $table->integer('created_by')->default('0');
            $table->integer('payment_id')->default('0');
            $table->string('category')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
