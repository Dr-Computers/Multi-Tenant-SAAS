<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('type'); // Asset Type
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('set null'); // Property ID (nullable foreign key)
            $table->string('location')->nullable(); // Location (nullable)
            $table->decimal('purchase_price', 10, 2)->nullable(); // Purchase Price (nullable)
            $table->string('vendor_name')->nullable(); // Vendor Name (nullable)
            $table->decimal('initial_value', 10, 2)->nullable(); // Initial Value (nullable)
            $table->decimal('current_market_value', 10, 2)->nullable(); // Current Market Value (nullable)
            $table->decimal('accumulated_depreciation', 10, 2)->default(0)->nullable(); // Accumulated Depreciation (not nullable)
            $table->string('owner_name')->nullable(); // Owner Name (nullable)
            $table->string('title_deed_number')->nullable(); // Title Deed Number (nullable)
            $table->string('condition')->nullable(); // Asset Condition (nullable)
            $table->string('status')->nullable(); // Status (nullable)
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'property_id',
                'location',
                'purchase_price',
                'vendor_name',
                'initial_value',
                'current_market_value',
                'accumulated_depreciation',
                'owner_name',
                'title_deed_number',
                'condition',
                'status',
                'company_id',
                'reference_no'
            ]);
        });
    }
};
