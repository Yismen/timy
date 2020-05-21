<?php

namespace Dainsys\Timy\Tests;

use App\User;
use Dainsys\Timy\Models\Disposition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class TimyDispositionTests extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_dispositions()
    {
        $dispositions = factory(Disposition::class, 10)->create();
        $dispositions = $dispositions->sortBy('name');

        $this->get(route('timy_dispositions.index', ['api_token' => $this->user->api_token]))
            ->assertOk()
            ->assertJson([
                'data' => []
            ])
            ->assertJsonCount(10, 'data');
    }

    /** @test */
    public function user_can_see_a_single_dispositions()
    {
        $disposition = factory(Disposition::class)->create();

        $this->get(route('timy_dispositions.show', ['timy_disposition' => $disposition->id, 'api_token' => $this->user->api_token]))
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

        $this->post(route('timy_dispositions.store', ['api_token' => $this->user->api_token]), $disposition)
            ->assertOk()
            ->assertJson([
                'data' => $disposition
            ]);

        $this->assertDatabaseHas('timy_dispositions', $disposition);
    }

    /** @test */
    public function a_disposition_can_be_updated()
    {
        $disposition = factory(Disposition::class)->create();

        $this->put(route('timy_dispositions.update', ['timy_disposition' => $disposition->id, 'api_token' => $this->user->api_token]), [
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
            'name' => 'Updated Name'
        ]);
    }

    /** @test */
    public function name_is_required_to_create_a_disposition()
    {
        $this->post(route('timy_dispositions.store', ['api_token' => $this->user->api_token]), ['name' => null])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function name_is_required_to_update_a_disposition()
    {
        $disposition = factory(Disposition::class)->create();

        $this->put(route('timy_dispositions.update', ['timy_disposition' => $disposition->id, 'api_token' => $this->user->api_token]), ['name' => null])
            ->assertSessionHasErrors(['name']);
    }
}
