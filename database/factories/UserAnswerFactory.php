<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAnswer>
 */
class UserAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_test_id' => fake()->numberBetween(1, 5),
            'question_id' => fake()->numberBetween(1, 5),
            'question_option_id' => fake()->numberBetween(1, 5),
        ];
    }
}
