<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Cuisine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CuisineController
 */
final class CuisineControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $cuisines = Cuisine::factory()->count(3)->create();

        $response = $this->get(route('cuisines.index'));

        $response->assertOk();
        $response->assertViewIs('cuisine.index');
        $response->assertViewHas('cuisines');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $cuisine = Cuisine::factory()->create();

        $response = $this->get(route('cuisines.show', $cuisine));

        $response->assertOk();
        $response->assertViewIs('cuisine.show');
        $response->assertViewHas('cuisine');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CuisineController::class,
            'store',
            \App\Http\Requests\CuisineControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name();

        $response = $this->post(route('cuisines.store'), [
            'name' => $name,
        ]);

        $cuisines = Cuisine::query()
            ->where('name', $name)
            ->get();
        $this->assertCount(1, $cuisines);
        $cuisine = $cuisines->first();

        $response->assertRedirect(route('cuisine.index'));
        $response->assertSessionHas('cuisine.name', $cuisine->name);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CuisineController::class,
            'update',
            \App\Http\Requests\CuisineControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $cuisine = Cuisine::factory()->create();
        $name = $this->faker->name();

        $response = $this->put(route('cuisines.update', $cuisine), [
            'name' => $name,
        ]);

        $cuisine->refresh();

        $response->assertRedirect(route('cuisine.show', [$cuisine]));
        $response->assertSessionHas('cuisine.name', $cuisine->name);

        $this->assertEquals($name, $cuisine->name);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $cuisine = Cuisine::factory()->create();

        $response = $this->delete(route('cuisines.destroy', $cuisine));

        $response->assertRedirect(route('cuisine.index'));
        $response->assertSessionHas('cuisine.name', $cuisine->name);

        $this->assertModelMissing($cuisine);
    }
}
