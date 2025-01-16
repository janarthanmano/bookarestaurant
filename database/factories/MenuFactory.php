<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Menu;

class MenuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Menu::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'created_at' => $this->faker->dateTime(),
            'description' => $this->faker->text(),
            'display_text' => $this->faker->boolean(),
            'image' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'thumbnail' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'is_vegan' => $this->faker->boolean(),
            'is_vegetarian' => $this->faker->boolean(),
            'name' => $this->faker->name(),
            'status' => $this->faker->numberBetween(-10000, 10000),
            'price_per_person' => $this->faker->randomFloat(0, 0, 9999999999.),
            'min_spend' => $this->faker->randomFloat(0, 0, 9999999999.),
            'is_seated' => $this->faker->boolean(),
            'is_standing' => $this->faker->boolean(),
            'is_canape' => $this->faker->boolean(),
            'is_mixed_dietary' => $this->faker->boolean(),
            'is_meal_prep' => $this->faker->boolean(),
            'is_halal' => $this->faker->boolean(),
            'is_kosher' => $this->faker->boolean(),
            'price_includes' => $this->faker->text(),
            'highlight' => $this->faker->text(),
            'available' => $this->faker->boolean(),
            'number_of_orders' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
