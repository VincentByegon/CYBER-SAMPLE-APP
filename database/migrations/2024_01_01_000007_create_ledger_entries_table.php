<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->morphs('reference'); // Can reference orders, payments, invoices
            $table->enum('type', ['debit', 'credit']);
            $table->decimal('amount', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->text('description');
            $table->timestamp('transaction_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
