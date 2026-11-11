<?php

namespace Database\Factories;

use App\Models\Statusreports;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
* @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Statusreports>
*/
class StatusreportsFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Statusreports::class;

    /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'basis' => $this->faker->word(),
            'total_sites' => $this->faker->numberBetween(1, 1000),
            'done' => $this->faker->numberBetween(1, 1000),
            'cant_be_reached' => $this->faker->numberBetween(1, 1000),
            'offline' => $this->faker->numberBetween(1, 1000),
            'not_found' => $this->faker->numberBetween(1, 1000),
            'users_id' => $this->faker->numberBetween(1, 1000),
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