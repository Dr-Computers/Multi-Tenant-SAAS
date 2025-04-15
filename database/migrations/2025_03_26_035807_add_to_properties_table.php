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
        if (!Schema::hasColumn('properties', 'moderation_status')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->string('mode')->nullable(1)->after('purpose_type');  
                $table->integer('author_id')->default(1)->after('thumbnail_image');   
                $table->integer('moderation_status')->default(1)->after('author_id');   
                $table->string('unique_id')->default(1)->after('moderation_status'); 
                $table->string('location')->nullable()->after('unique_id');   
                $table->string('latitude')->nullable()->after('location');   
                $table->string('longitude')->nullable()->after('latitude');   
                $table->string('city')->nullable()->after('latitude');   
                $table->string('locality')->nullable()->after('city');   
                $table->string('sub_locality')->nullable()->after('locality');   
                $table->mediumText('description')->nullable()->after('sub_locality');   
                $table->integer('views')->default(0)->after('description');   
                $table->boolean('is_featured')->default(0)->after('views');  
                $table->string('plot_area')->nullable()->after('is_featured');
                $table->string('open_sides')->nullable()->after('plot_area');
                $table->string('plot_type')->nullable()->after('open_sides');
                
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            //
        });
    }
};
