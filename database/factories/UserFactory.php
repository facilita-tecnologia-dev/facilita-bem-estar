<?php

namespace Database\Factories;

use App\Enums\DepartmentEnum;
use App\Enums\DepartmentEnumGenderEnum;
use App\Enums\GenderEnum;
use App\Models\Company;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('pt_BR');

        return [
            'name' => $faker->name(),
            'cpf' => $faker->unique()->cpf(),
            'birth_date' => $faker->date(),
            'gender' => $faker->randomElement(GenderEnum::cases())->value,
            'department' => $faker->randomElement(DepartmentEnum::cases())->value,
            'occupation' => $faker->word(),
            'admission' => $faker->date(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($user) {
            $company = Company::first();
            $user->companies()->attach($company->id, [
                'role_id' => rand(1, 2),
            ]);
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
