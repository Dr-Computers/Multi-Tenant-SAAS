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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->text('action')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('reference_url')->nullable();
            $table->text('reference_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->mediumText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
