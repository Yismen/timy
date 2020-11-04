<?php

namespace Dainsys\Timy\Tests\Feature;

use Dainsys\Timy\Models\Disposition;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait TimyDispositionTestsTrait
{
    /** @test */
    public function a_disposition_can_be_created()
    {
        $disposition = factory(Disposition::class)->make()->toArray();

        $this->actingAs($this->superAdminUser())->post(route('timy_web_disposition.store'), $disposition)
            ->assertRedirect(route('super_admin_dashboard'));

        $this->assertDatabaseHas('timy_dispositions', ['name' => strtolower($disposition['name'])]);
    }

    /** @test */
    public function a_disposition_can_be_updated()
    {
        $disposition = factory(Disposition::class)->create();

        $this->actingAs($this->superAdminUser())
            ->put(route('timy_web_disposition.update', $disposition->id), [
                'name' => 'Updated Name'
            ])
            ->assertRedirect(route('super_admin_dashboard'));

        $this->assertDatabaseHas('timy_dispositions', [
            'name' => 'updated name'
        ]);
    }

    /** @test */
    public function name_is_required_to_create_a_disposition()
    {
        $this->actingAs($this->user())->post(route('timy_web_disposition.store'), ['name' => null])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function name_is_required_to_update_a_disposition()
    {
        $disposition = factory(Disposition::class)->create();

        $this->actingAs($this->user())->put(route('timy_web_disposition.update', $disposition->id), ['name' => null])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function name_is_must_be_unique_to_create_a_disposition()
    {
        factory(Disposition::class)->create(['name' => 'same name']);

        $this->actingAs($this->user())->post(route('timy_web_disposition.store'), ['name' => 'same name'])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function name_is_must_be_unique_to_update_a_disposition_except_if_same_id()
    {
        $disposition = factory(Disposition::class)->create();
        $disposition2 = factory(Disposition::class)->create();

        // Try to update disposition 2 using same name as disposition 1 should fail validation
        $this->actingAs($this->user())->put(route('timy_web_disposition.update', $disposition2->id), ['name' => strtolower($disposition->name)])
            ->assertSessionHasErrors(['name']);

        $this->actingAs($this->user())->put(route('timy_web_disposition.update', $disposition->id), ['name' => strtolower($disposition->name)])
            ->assertSessionDoesntHaveErrors(['name']);
    }
}
