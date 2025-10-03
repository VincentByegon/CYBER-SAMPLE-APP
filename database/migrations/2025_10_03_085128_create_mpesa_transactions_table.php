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
        Schema::create('mpesa_transactions', function (Blueprint $table) {
   $table->id();
    $table->string('transaction_id')->unique();
    $table->decimal('amount', 10, 2);
    $table->string('phone');
    $table->string('account')->nullable();
    $table->string('first_name')->nullable();
    $table->string('middle_name')->nullable();
    $table->string('last_name')->nullable();
    $table->string('status')->default('completed');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_transactions');
    }
};
