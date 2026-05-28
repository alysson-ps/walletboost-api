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
        Schema::create('participant_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('transaction_participant_id')
                ->constrained('transaction_participants')
                ->cascadeOnDelete();

            $table->foreignId('payment_transaction_id')->nullable()
                ->constrained('transactions')
                ->nullOnDelete();

            $table->decimal('amount', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_payments');
    }
};
