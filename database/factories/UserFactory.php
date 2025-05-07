<?php

namespace Database\Factories;

use App\Enums\DepartmentEnum;
use App\Enums\EducationLevelEnum;
use App\Enums\GenderEnum;
use App\Enums\MaritalStatusEnum;
use App\Enums\OccupationEnum;
use App\Enums\WorkShiftEnum;
use App\Models\Company;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'birth_date' => $faker->date($format = 'Y-m-d', $max = '2007-01-01', $min = '1930-01-01'),
            'gender' => $faker->randomElement(GenderEnum::cases())->value,
            'department' => $faker->randomElement(DepartmentEnum::cases())->value,
            'occupation' => $faker->randomElement(OccupationEnum::cases())->value,
            'admission' => $faker->date($format = 'Y-m-d', $max = 'now', $min = '1930-01-01'),
            'marital_status' => $faker->randomElement(MaritalStatusEnum::cases())->value,
            'work_shift' => $faker->randomElement(WorkShiftEnum::cases())->value,
            'education_level' => $faker->randomElement(EducationLevelEnum::cases())->value,
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
