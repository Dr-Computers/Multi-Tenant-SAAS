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
        Schema::create('property_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->nullable();    
            $table->string('field_name')->nullable(); 
            $table->mediumText('field_value')->nullable(); 
            $table->integer('property_id')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_custom_fields');
    }
};
