<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'user_coupons',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user');
                $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
                $table->integer('coupon');
                $table->string('order')->nullable();
                $table->timestamps();
            }
        );

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('coupon_id')->constrained('coupons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_coupons');
    }
}
