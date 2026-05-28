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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->noActionOnDelete();

            $table->foreignId('account_id')
                ->constrained('accounts')
                ->noActionOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->noActionOnDelete();

            $table->foreignId('recurrence_id')->nullable()
                ->constrained('recurrences')
                ->noActionOnDelete();

            $table->enum('type', ['income', 'expense', 'transfer', 'investment']);

            $table->decimal('amount', 15, 2);
            $table->string('description');

            $table->date('occurred_at');
            $table->enum('status', ['pending', 'paid', 'partial']);

            $table->foreignId('parent_id')->nullable()
                ->constrained('transactions')
                ->noActionOnDelete();

            $table->integer('installments_number')->nullable();
            $table->integer('installment_total')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
