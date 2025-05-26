<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liabilities', function (Blueprint $table) {
            $table->id(); // Liability ID (auto-incrementing primary key)
            $table->string('name'); // Liability Name/Description
            $table->string('type'); // Liability Type (e.g., loan, credit, etc.)
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('set null'); // Property ID (nullable foreign key)
            $table->decimal('amount', 10, 2); // Amount of the liability (not nullable)
            $table->date('due_date')->nullable(); // Due Date (nullable)
            $table->string('vendor_name')->nullable(); // Vendor Name (nullable)
            $table->decimal('interest_rate', 5, 2)->nullable(); // Interest Rate (nullable)
            $table->string('payment_terms')->nullable(); // Payment Terms (nullable)
            $table->text('notes')->nullable(); // Additional Notes/Comments (nullable)
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->nullable(); // Status (nullable)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liabilities');
    }
};
