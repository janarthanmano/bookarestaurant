<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\MenuCuisine;

class MenuCuisineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MenuCuisine::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'menu_id' => $this->faker->word(),
            'cuisine_id' => $this->faker->word(),
        ];
    }
}
