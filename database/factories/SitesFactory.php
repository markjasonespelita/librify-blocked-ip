<?php

namespace Database\Factories;

use App\Models\Sites;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
* @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sites>
*/
class SitesFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Sites::class;

    /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'municipalities_id' => $this->faker->numberBetween(1, 1000),
            'olts_id' => $this->faker->numberBetween(1, 1000),
            'statuses_id' => $this->faker->numberBetween(1, 1000),
            'ip_address' => $this->faker->word(),
            'remarks' => $this->faker->word(),
            'isTrash' => '0',
        ];
    }

    /**
    * Indicate that the model is in trash.
    */
    public function trashed(): static
    {
        return $this->state(fn (array $attributes) => [
            'isTrash' => '1',
        ]);
    }
}