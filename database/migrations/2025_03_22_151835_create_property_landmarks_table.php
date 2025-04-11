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
        Schema::create('property_landmarks', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->nullable();    
            $table->integer('landmark_id')->nullable(); 
            $table->mediumText('landmark_value')->nullable(); 
            $table->integer('property_id')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_landmarks');
    }
};
