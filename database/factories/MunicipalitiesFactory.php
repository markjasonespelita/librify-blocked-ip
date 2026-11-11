<?php

namespace Database\Factories;

use App\Models\Municipalities;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
* @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Municipalities>
*/
class MunicipalitiesFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Municipalities::class;

    /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
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