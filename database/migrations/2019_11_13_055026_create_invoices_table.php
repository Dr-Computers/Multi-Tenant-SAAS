<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'invoices',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('invoice_id');
                $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
                $table->date('issue_date');
                $table->date('due_date');
                $table->date('send_date')->nullable();
                $table->integer('category_id')->default('0');
                // $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
                $table->text('ref_number')->nullable();
                $table->integer('status')->default('0');
                $table->integer('shipping_display')->default('1');
                $table->string('coupon_code')->nullable();
                $table->string('discount_amount')->nullable();
                $table->decimal('sub_total',8,2)->nullable();
                $table->decimal('grand_total',8,)->nullable();
                $table->integer('discount_apply')->default('0');
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('invoices');
    }
}
