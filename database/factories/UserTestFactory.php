<?php

namespace Database\Factories;

use App\Models\UserTest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserTest>
 */
class UserTestFactory extends Factory
{
    protected $model = UserTest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'test_id' => fake()->uuid(),
            'score' => fake()->numberBetween(10, 120),
            'severity_title' => 1,
            'severity_color' => 1
        ];
    }
}
