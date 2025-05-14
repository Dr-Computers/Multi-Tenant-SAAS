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
        Schema::create('property_maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('unit_id')->nullable();
            $table->integer('issue_type')->nullable();
            $table->integer('maintainer_id')->nullable();
            $table->decimal('amount', 8, 4)->nullable();
            $table->integer('status')->nullable();
            $table->string('issue_attachment')->nullable();
            $table->dateTime('invoice_published')->nullable();
            $table->dateTime('invoice_due_date')->nullable();
            $table->dateTime('request_date')->nullable();
            $table->dateTime('fixed_date')->nullable();
            $table->longText('notes')->nullable();
            $table->string('parent_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_maintenance_requests');
    }
};
