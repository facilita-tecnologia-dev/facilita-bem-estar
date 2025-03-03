<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create([
            'gender' => 'Masculino',
            'position' => 'Operador de prensa',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'gender' => 'Masculino',
            'position' => 'Operador de prensa',
            'last_test_date' => Date::now(),
        ]);
    }
}
