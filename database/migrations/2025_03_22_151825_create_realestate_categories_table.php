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
        Schema::create('realestate_categories', function (Blueprint $table) {
            $table->id();     
            $table->integer('icon_id')->nullable();    
            $table->string('name')->nullable();         
            $table->boolean('is_sell')->nullable();  
            $table->boolean('is_rent')->nullable(); 
            $table->boolean('is_commercial')->nullable();  
            $table->boolean('is_residential')->nullable(); 
            $table->boolean('status')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realestate_categories');
    }
};
