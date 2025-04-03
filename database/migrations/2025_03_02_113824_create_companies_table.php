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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('bussiness_name')->nullable();
            $table->string('bussiness_type')->nullable();
            $table->string('address')->nullable();
            $table->string('landmark')->nullable();
            $table->string('postalcode')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('reg_no')->nullable();
            $table->string('gst')->nullable();
            $table->string('vat')->nullable();
            $table->string('identify_code')->nullable();
            $table->string('plan_order_id')->nullable();
            $table->timestamps();
            $table->string('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
