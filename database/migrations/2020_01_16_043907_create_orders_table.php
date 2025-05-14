<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->string('order_id',100)->unique();
            $table->string('plan_name',100);
            $table->integer('plan_id');
            $table->dateTime('plan_expire_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default('0.0');
            $table->decimal('tax', 15, 2)->default('0.0');
            $table->decimal('discount', 15, 2)->default('0.0');
            $table->string('coupon_code')->nullable();
            $table->decimal('price', 15, 2)->default('0.0');
            $table->string('price_currency',10);
            $table->string('txn_id',100);
            $table->string('payment_status',100);
            $table->string('payment_type')->default('Manually');
            $table->string('receipt')->nullable();
            $table->integer('user_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
