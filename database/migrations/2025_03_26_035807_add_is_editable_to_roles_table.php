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
        if (!Schema::hasColumn('roles', 'is_editable')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->boolean('is_editable')->default(1)->after('created_by');   
                $table->boolean('is_deletable')->default(1)->after('is_editable');  
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            //
        });
    }
};
