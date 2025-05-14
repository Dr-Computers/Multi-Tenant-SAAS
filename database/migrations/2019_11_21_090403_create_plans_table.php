<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',100)->nullable();
            $table->decimal('price', 15, 2)->default('0.0');
            $table->string('duration',100);
            $table->integer('max_users')->default(0);
            $table->integer('max_owners')->default(0);
            $table->integer('max_tenants')->default(0);
            $table->float('storage_limit')->default('0.00');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('enable_chatgpt')->default('off');
            $table->string('business_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
