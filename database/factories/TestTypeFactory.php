<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestType>
 */
class TestTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key_name' => fake()->name(),
            'display_name' => fake()->name(),
            'number_of_questions' => fake()->numberBetween(1,9),
            'handler_type' => fake()->word(),
        ];
    }
}
