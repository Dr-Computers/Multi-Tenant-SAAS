<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionPlanRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *installation
     * @return void
     */
    public function up()
    {
        // Schema::table('users', function (Blueprint $table){
        //     $table->integer('requested_plan')->default(0)->after('plan_expire_date');
        // });

        Schema::create('section_plan_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->string('section_ids')->nullable();
            $table->string('duration', 20)->default('monthly');
            $table->string('coupon')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->integer('discount')->nullable();
            $table->decimal('tax_total', 8, 2)->nullable();
            $table->decimal('sub_total', 8, 2)->nullable();
            $table->decimal('grand_total', 8, 2)->nullable();
            $table->string('status')->default('pending')->nullable();
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
        Schema::dropIfExists('section_plan_requests');
    }
}
