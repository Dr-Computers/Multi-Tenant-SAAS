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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();

            $table->string('ticket_no', 50); 
            $table->text('subject'); 
            $table->text('body'); 
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('status')->default(1); 
            $table->enum('to', ['admin', 'company'])->nullable();
            $table->dateTime('read_at')->nullable();
            $table->string('type', 25)->nullable();
            $table->string('priority', 10)->nullable();
            $table->string('staff_id', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
