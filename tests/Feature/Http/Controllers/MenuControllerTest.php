<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\MenuController
 */
final class MenuControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $menus = Menu::factory()->count(3)->create();

        $response = $this->get(route('menus.index'));

        $response->assertOk();
        $response->assertViewIs('menu.index');
        $response->assertViewHas('menus');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $menu = Menu::factory()->create();

        $response = $this->get(route('menus.show', $menu));

        $response->assertOk();
        $response->assertViewIs('menu.show');
        $response->assertViewHas('menu');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\MenuController::class,
            'store',
            \App\Http\Requests\MenuControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name();
        $description = $this->faker->text();
        $price_per_person = $this->faker->randomFloat(/** decimal_attributes **/);
        $min_spend = $this->faker->randomFloat(/** decimal_attributes **/);
        $created_at = Carbon::parse($this->faker->dateTime());
        $display_text = $this->faker->boolean();
        $image = $this->faker->word();
        $thumbnail = $this->faker->word();
        $is_vegan = $this->faker->boolean();
        $is_vegetarian = $this->faker->boolean();
        $status = $this->faker->numberBetween(-10000, 10000);
        $is_seated = $this->faker->boolean();
        $is_standing = $this->faker->boolean();
        $is_canape = $this->faker->boolean();
        $is_mixed_dietary = $this->faker->boolean();
        $is_meal_prep = $this->faker->boolean();
        $is_halal = $this->faker->boolean();
        $is_kosher = $this->faker->boolean();
        $price_includes = $this->faker->text();
        $highlight = $this->faker->text();
        $available = $this->faker->boolean();
        $number_of_orders = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('menus.store'), [
            'name' => $name,
            'description' => $description,
            'price_per_person' => $price_per_person,
            'min_spend' => $min_spend,
            'created_at' => $created_at->toDateTimeString(),
            'display_text' => $display_text,
            'image' => $image,
            'thumbnail' => $thumbnail,
            'is_vegan' => $is_vegan,
            'is_vegetarian' => $is_vegetarian,
            'status' => $status,
            'is_seated' => $is_seated,
            'is_standing' => $is_standing,
            'is_canape' => $is_canape,
            'is_mixed_dietary' => $is_mixed_dietary,
            'is_meal_prep' => $is_meal_prep,
            'is_halal' => $is_halal,
            'is_kosher' => $is_kosher,
            'price_includes' => $price_includes,
            'highlight' => $highlight,
            'available' => $available,
            'number_of_orders' => $number_of_orders,
        ]);

        $menus = Menu::query()
            ->where('name', $name)
            ->where('description', $description)
            ->where('price_per_person', $price_per_person)
            ->where('min_spend', $min_spend)
            ->where('created_at', $created_at)
            ->where('display_text', $display_text)
            ->where('image', $image)
            ->where('thumbnail', $thumbnail)
            ->where('is_vegan', $is_vegan)
            ->where('is_vegetarian', $is_vegetarian)
            ->where('status', $status)
            ->where('is_seated', $is_seated)
            ->where('is_standing', $is_standing)
            ->where('is_canape', $is_canape)
            ->where('is_mixed_dietary', $is_mixed_dietary)
            ->where('is_meal_prep', $is_meal_prep)
            ->where('is_halal', $is_halal)
            ->where('is_kosher', $is_kosher)
            ->where('price_includes', $price_includes)
            ->where('highlight', $highlight)
            ->where('available', $available)
            ->where('number_of_orders', $number_of_orders)
            ->get();
        $this->assertCount(1, $menus);
        $menu = $menus->first();

        $response->assertRedirect(route('menu.index'));
        $response->assertSessionHas('menu.name', $menu->name);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\MenuController::class,
            'update',
            \App\Http\Requests\MenuControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $menu = Menu::factory()->create();
        $name = $this->faker->name();
        $description = $this->faker->text();
        $price_per_person = $this->faker->randomFloat(/** decimal_attributes **/);
        $min_spend = $this->faker->randomFloat(/** decimal_attributes **/);
        $created_at = Carbon::parse($this->faker->dateTime());
        $display_text = $this->faker->boolean();
        $image = $this->faker->word();
        $thumbnail = $this->faker->word();
        $is_vegan = $this->faker->boolean();
        $is_vegetarian = $this->faker->boolean();
        $status = $this->faker->numberBetween(-10000, 10000);
        $is_seated = $this->faker->boolean();
        $is_standing = $this->faker->boolean();
        $is_canape = $this->faker->boolean();
        $is_mixed_dietary = $this->faker->boolean();
        $is_meal_prep = $this->faker->boolean();
        $is_halal = $this->faker->boolean();
        $is_kosher = $this->faker->boolean();
        $price_includes = $this->faker->text();
        $highlight = $this->faker->text();
        $available = $this->faker->boolean();
        $number_of_orders = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('menus.update', $menu), [
            'name' => $name,
            'description' => $description,
            'price_per_person' => $price_per_person,
            'min_spend' => $min_spend,
            'created_at' => $created_at->toDateTimeString(),
            'display_text' => $display_text,
            'image' => $image,
            'thumbnail' => $thumbnail,
            'is_vegan' => $is_vegan,
            'is_vegetarian' => $is_vegetarian,
            'status' => $status,
            'is_seated' => $is_seated,
            'is_standing' => $is_standing,
            'is_canape' => $is_canape,
            'is_mixed_dietary' => $is_mixed_dietary,
            'is_meal_prep' => $is_meal_prep,
            'is_halal' => $is_halal,
            'is_kosher' => $is_kosher,
            'price_includes' => $price_includes,
            'highlight' => $highlight,
            'available' => $available,
            'number_of_orders' => $number_of_orders,
        ]);

        $menu->refresh();

        $response->assertRedirect(route('menu.show', [$menu]));
        $response->assertSessionHas('menu.name', $menu->name);

        $this->assertEquals($name, $menu->name);
        $this->assertEquals($description, $menu->description);
        $this->assertEquals($price_per_person, $menu->price_per_person);
        $this->assertEquals($min_spend, $menu->min_spend);
        $this->assertEquals($created_at->timestamp, $menu->created_at);
        $this->assertEquals($display_text, $menu->display_text);
        $this->assertEquals($image, $menu->image);
        $this->assertEquals($thumbnail, $menu->thumbnail);
        $this->assertEquals($is_vegan, $menu->is_vegan);
        $this->assertEquals($is_vegetarian, $menu->is_vegetarian);
        $this->assertEquals($status, $menu->status);
        $this->assertEquals($is_seated, $menu->is_seated);
        $this->assertEquals($is_standing, $menu->is_standing);
        $this->assertEquals($is_canape, $menu->is_canape);
        $this->assertEquals($is_mixed_dietary, $menu->is_mixed_dietary);
        $this->assertEquals($is_meal_prep, $menu->is_meal_prep);
        $this->assertEquals($is_halal, $menu->is_halal);
        $this->assertEquals($is_kosher, $menu->is_kosher);
        $this->assertEquals($price_includes, $menu->price_includes);
        $this->assertEquals($highlight, $menu->highlight);
        $this->assertEquals($available, $menu->available);
        $this->assertEquals($number_of_orders, $menu->number_of_orders);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $menu = Menu::factory()->create();

        $response = $this->delete(route('menus.destroy', $menu));

        $response->assertRedirect(route('menu.index'));
        $response->assertSessionHas('menu.name', $menu->name);

        $this->assertModelMissing($menu);
    }
}
