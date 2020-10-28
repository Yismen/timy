<?php

namespace Dainsys\Timy\Tests\Feature\Traits;

use Dainsys\Timy\Disposition;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait TimyDispositionTestsTrait
{
    /** @test */
    public function guest_are_unauthorized()
    {
        $this->get(route('timy_dispositions.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_see_dispositions()
    {
        factory(Disposition::class, 10)->create();
        $initial = config('timy.initial_dispositions');

        $this->actingAs($this->user())->get(route('timy_dispositions.index'))
            ->assertOk()
            ->assertJson([
                'data' => []
            ])
            ->assertJsonCount(10 + count($initial), 'data');
    }

    /** @test */
    public function user_can_see_a_single_dispositions()
    {
        $disposition = factory(Disposition::class)->create();

        $this->actingAs($this->user())->get(route('timy_dispositions.show', $disposition->id))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $disposition->id,
                    'name' => $disposition->name,
                    'invoiceable' => $disposition->invoiceable,
                ]
            ]);
    }

    /** @test */
    public function a_disposition_can_be_created()
    {
        $disposition = factory(Disposition::class)->make()->toArray();

        $this->actingAs($this->user())->post(route('timy_dispositions.store'), $disposition)
            ->assertOk()
            ->assertJson([
                'data' => $disposition
            ]);

        $this->assertDatabaseHas('timy_dispositions', ['name' => strtolower($disposition['name'])]);
    }

    /** @test */
    public function a_disposition_can_be_updated()
    {
        $disposition = factory(Disposition::class)->create();

        $this->actingAs($this->user())->put(route('timy_dispositions.update', $disposition->id), [
            'name' => 'Updated Name'
        ])
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $disposition->id,
                    'name' => 'Updated Name',
                    'invoiceable' => $disposition->invoiceable,
                ]
            ]);

        $this->assertDatabaseHas('timy_dispositions', [
            'name' => 'updated name'
        ]);
    }

    /** @test */
    public function name_is_required_to_create_a_disposition()
    {
        $this->actingAs($this->user())->post(route('timy_dispositions.store'), ['name' => null])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function name_is_required_to_update_a_disposition()
    {
        $disposition = factory(Disposition::class)->create();

        $this->actingAs($this->user())->put(route('timy_dispositions.update', $disposition->id), ['name' => null])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function name_is_must_be_unique_to_create_a_disposition()
    {
        factory(Disposition::class)->create(['name' => 'same name']);

        $this->actingAs($this->user())->post(route('timy_dispositions.store'), ['name' => 'same name'])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function name_is_must_be_unique_to_update_a_disposition_except_if_same_id()
    {
        $disposition = factory(Disposition::class)->create();
        $disposition2 = factory(Disposition::class)->create();

        // Try to update disposition 2 using same name as disposition 1 should fail validation
        $this->actingAs($this->user())->put(route('timy_dispositions.update', $disposition2->id), ['name' => strtolower($disposition->name)])
            ->assertSessionHasErrors(['name']);

        $this->actingAs($this->user())->put(route('timy_dispositions.update', $disposition->id), ['name' => strtolower($disposition->name)])
            ->assertSessionDoesntHaveErrors(['name']);
    }
}
