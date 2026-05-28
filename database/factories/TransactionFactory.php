<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'account_id' => fake()->numberBetween(1, 3),
            'category_id' => fake()->numberBetween(1, 9),
            'recurrence_id' => null,
            'type' => fake()->randomElement(['income', 'expense', 'transfer', 'investment']),
            'amount' => fake()->randomFloat(2, 1, 1000),
            'description' => fake()->sentence(),
            'occurred_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'status' => fake()->randomElement(['pending', 'paid', 'partial']),
            'parent_id' => null,
            'installments_number' => null,
            'installment_total' => null,
        ];
    }
}
