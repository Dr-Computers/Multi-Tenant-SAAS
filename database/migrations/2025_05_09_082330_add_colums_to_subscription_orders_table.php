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
        Schema::table('subscription_orders', function (Blueprint $table) {
            $table->integer('order_id')->nullable()->after('id');
            $table->integer('plan_id')->nullable()->after('order_id');
            $table->integer('company_id')->nullable()->after('id');
            $table->dateTime('start_of_date')->nullable()->after('plan_id');
            $table->dateTime('end_of_date')->nullable()->after('start_of_date');
            $table->integer('max_users')->default(0)->after('end_of_date');
            $table->integer('max_tenants')->default(0)->after('max_users');
            $table->integer('max_owners')->default(0)->after('max_tenants');
            $table->decimal('max_storage_capacity', 8, 2)->nullable()->after('max_owners');
            $table->tinyInteger('status')->default(1)->after('max_storage_capacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_orders', function (Blueprint $table) {
            $table->dropColumn([
                'company_id',
                'plan_id',
                'start_of_date',
                'end_of_date',
                'max_users',
                'max_tenants',
                'max_owners',
                'max_storage_capacity',
                'status',
            ]);
        });
    }
};
