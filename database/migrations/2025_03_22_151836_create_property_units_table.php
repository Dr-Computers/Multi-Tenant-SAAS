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
        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade'); 
            $table->string('name')->nullable();      
            $table->integer('kitchen')->nullable(); 
            $table->integer('bed_rooms')->nullable(); 
            $table->integer('bath_rooms')->nullable(); 
            $table->integer('balconies')->nullable();            
            $table->text('other_rooms')->nullable(); 
            $table->decimal('registration_no',8,4)->nullable();            
            $table->string('rent_type')->nullable(); 
            $table->integer('rent_duration')->nullable()->default(1)->comment('month'); 
            $table->decimal('price',8,2)->nullable();             
            $table->string('deposite_type')->nullable(); 
            $table->decimal('deposite_amount',8,2)->nullable();             
            $table->string('late_fee_type')->nullable(); 
            $table->decimal('late_fee_amount',8,2)->nullable();       
            $table->string('incident_reicept_amount')->nullable(); 
            $table->longText('notes')->nullable(); 
            $table->string('flooring')->nullable();         
            $table->string('price_included')->nullable(); 
            $table->string('youtube_video')->nullable(); 
            $table->mediumText('thumbnail_image')->nullable(); 
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_units');
    }
};
