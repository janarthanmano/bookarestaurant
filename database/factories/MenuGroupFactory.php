<?php

namespace Database\Factories;

use backup\MenuGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MenuGroup::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'menu_id' => $this->faker->word(),
            'group_id' => $this->faker->word(),
        ];
    }
}
