<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\GroupController
 */
final class GroupControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $groups = Group::factory()->count(3)->create();

        $response = $this->get(route('groups.index'));

        $response->assertOk();
        $response->assertViewIs('group.index');
        $response->assertViewHas('groups');
    }


    #[Test]
    public function show_displays_view(): void
    {
        $group = Group::factory()->create();

        $response = $this->get(route('groups.show', $group));

        $response->assertOk();
        $response->assertViewIs('group.show');
        $response->assertViewHas('group');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\GroupController::class,
            'store',
            \App\Http\Requests\GroupControllerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name();

        $response = $this->post(route('groups.store'), [
            'name' => $name,
        ]);

        $groups = Group::query()
            ->where('name', $name)
            ->get();
        $this->assertCount(1, $groups);
        $group = $groups->first();

        $response->assertRedirect(route('group.index'));
        $response->assertSessionHas('group.name', $group->name);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\GroupController::class,
            'update',
            \App\Http\Requests\GroupControllerUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $group = Group::factory()->create();
        $name = $this->faker->name();

        $response = $this->put(route('groups.update', $group), [
            'name' => $name,
        ]);

        $group->refresh();

        $response->assertRedirect(route('group.show', [$group]));
        $response->assertSessionHas('group.name', $group->name);

        $this->assertEquals($name, $group->name);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $group = Group::factory()->create();

        $response = $this->delete(route('groups.destroy', $group));

        $response->assertRedirect(route('group.index'));
        $response->assertSessionHas('group.name', $group->name);

        $this->assertModelMissing($group);
    }
}
