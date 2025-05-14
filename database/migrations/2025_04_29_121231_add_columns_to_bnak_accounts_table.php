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
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->string('account_type'); // e.g., savings, current
            $table->string('bank_branch')->nullable();
            $table->decimal('closing_balance', 15, 2)->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropColumn([
                'account_type',
                'bank_branch',
                'closing_balance',
                'email',
                'phone'
            ]);
        });
    }
};
