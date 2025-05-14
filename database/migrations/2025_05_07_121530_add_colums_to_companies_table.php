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
        Schema::table('companies', function (Blueprint $table) {
            //
            $table->decimal('storage_capacity', 8, 2)->nullable()->after('identify_code');
            $table->integer('max_staff')->nullable()->after('identify_code');
            $table->integer('max_tenants')->nullable()->after('identify_code');
            $table->integer('max_owners')->nullable()->after('identify_code');
            $table->string('currency')->nullable()->after('identify_code');
            $table->string('currency_code')->nullable()->after('identify_code');
            $table->string('logo_1')->nullable()->after('identify_code');
            $table->string('logo_2')->nullable()->after('identify_code');
            $table->string('signature')->nullable()->after('identify_code');
            $table->string('seal')->nullable()->after('identify_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
        });
    }
};
