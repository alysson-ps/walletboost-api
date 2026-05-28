<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Goal>
 */
class GoalFactory extends Factory
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
            'name' => fake()->sentence(3),
            'target_amount' => fake()->randomFloat(2, 100, 10000),
            'current_amount' => fake()->randomFloat(2, 0, 5000),
            'target_date' => fake()->dateTimeBetween('now', '+1 year'),
            'color' => fake()->hexColor(),
        ];
    }
}
