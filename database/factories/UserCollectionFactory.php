<?php

namespace Database\Factories;

use App\Models\UserCollection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserCollection>
 */
class UserCollectionFactory extends Factory
{
    protected $model = UserCollection::class;

    public function definition(): array
    {
        return [
            'collection_id' => $this->faker->randomElement([1, 2]),
        ];
    }

    // Estado para collection_id = 1
    public function withCollectionIdOne()
    {
        return $this->state([
            'collection_id' => 1,
        ]);
    }

    // Estado para collection_id = 2
    public function withCollectionIdTwo()
    {
        return $this->state([
            'collection_id' => 2,
        ]);
    }
}
