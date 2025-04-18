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
        if(!Schema::hasTable('referral_transaction_orders'))
        {
            Schema::create('referral_transaction_orders', function (Blueprint $table) {
                $table->id();
                $table->decimal('req_amount',15,2)->default(0.0);
                $table->integer('req_user_id');
                $table->integer('status')->default(0);
                $table->date('date')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_transaction_orders');
    }
};
