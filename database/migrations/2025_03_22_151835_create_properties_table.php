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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();      
            $table->string('purpose_type')->nullable(); 
            $table->string('ownership')->nullable(); 
            $table->integer('total_floor')->nullable(); 
            $table->integer('available_floor')->nullable();            
            $table->decimal('carpet_area',8,4)->nullable(); 
            $table->decimal('super_buit_up_area',8,4)->nullable();            
            $table->integer('closed_parking')->nullable(); 
            $table->integer('open_parking')->nullable();             
            $table->string('availability_status')->nullable(); 
            $table->string('age_property')->nullable();             
            $table->string('building_mo')->nullable(); 
            $table->integer('lifts')->nullable();       
            $table->string('maintatenance_type')->nullable(); 
            $table->string('maintatenace_fee')->nullable(); 
            $table->string('overlooking')->nullable();         
            $table->string('water_availability')->nullable(); 
            $table->string('status_electricity')->nullable(); 
            $table->string('authority_approvel')->nullable(); 
            $table->integer('authority_approvel_document_id')->nullable(); 
            $table->string('fire_safty_start_date')->nullable();         
            $table->string('fire_safty_end_date')->nullable(); 
            $table->string('insurance_start_date')->nullable(); 
            $table->string('insurance_end_date')->nullable(); 
            $table->string('youtube_video')->nullable(); 
            $table->mediumText('thumbnail_image')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
