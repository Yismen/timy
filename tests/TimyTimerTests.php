<?php

namespace Dainsys\Timy\Tests;

use App\User;
use Carbon\Carbon;
use Dainsys\Timy\Models\Timer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class TimyTimerTests extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_only_see_own_timers()
    {
        $this->user2 = factory(\App\User::class)->create();
        $this->actingAs($this->user);

        factory(Timer::class, 5)->create(['user_id' => $this->user->id]);
        factory(Timer::class, 5)->create(['user_id' => $this->user2->id]);

        $this->get(route('timy_timers.index', ['api_token' => $this->user->api_token]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'user_id' => $this->user->id
                    ]
                ],
                'to' => 5,
                'per_page' => 50
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        'user_id' => $this->user2->id
                    ]
                ]
            ]);
    }

    /** @test */
    public function user_can_see_a_single_timers()
    {
        $this->actingAs($this->user);
        $timer = factory(Timer::class)->create(['user_id' => $this->user->id]);

        $this->get(route('timy_timers.show', ['timy_timer' => $timer->id, 'api_token' => $this->user->api_token]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $timer->id,
                    'task_id' => $timer->task_id,
                ]
            ]);
    }

    /** @test */
    public function a_timer_can_be_created()
    {
        $this->actingAs($this->user);
        $timer = factory(Timer::class)->make([
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'started_at' => now()->subDays(8)
        ])->toArray();
        $parsed_date = Carbon::parse($timer['started_at'])->format('Y-m-d H:i:s');

        $this->post(route('timy_timers.store', ['api_token' => $this->user->api_token]), $timer)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'user_id' => $timer['user_id'],
                    'task_id' => $timer['task_id'],
                ]
            ]);

        $this->assertDatabaseHas('timy_timers', [
            'user_id' => $timer['user_id'],
            'task_id' => $timer['task_id']
        ]);

        $this->assertDatabaseMissing('timy_timers', [
            'started_at' => $parsed_date
        ]);
    }

    /** @test */
    public function a_timer_can_have_only_one_timer_running()
    {
        $this->actingAs($this->user);
        factory(Timer::class, 5)->create(['user_id' => $this->user->id, 'finished_at' => null]);
        $timer = factory(Timer::class)->make(['user_id' => $this->user->id, 'finished_at' => null])->toArray();

        $this->post(route('timy_timers.store', ['api_token' => $this->user->api_token]), $timer);

        $this->assertCount(6, $this->user->timers()->get());
        $this->assertCount(1, $this->user->timers()->running()->get());
    }

    /** @test */
    public function a_timer_can_be_updated()
    {
        $this->actingAs($this->user);
        $timer = factory(Timer::class)->create(['user_id' => $this->user->id, 'finished_at' => null]);

        $this->put(route('timy_timers.update', ['timy_timer' => $timer->id, 'api_token' => $this->user->api_token]), [
            'finished_at' => now()
        ])
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $timer->id
                ]
            ]);

        $this->assertDatabaseHas('timy_timers', [
            'finished_at' => now()
        ]);
    }

    /** @test */
    public function task_id_is_exists_to_create_a_timer()
    {
        $this->post(route('timy_timers.store', ['api_token' => $this->user->api_token]), ['task_id' => null])
            ->assertSessionHasErrors(['task_id']);
    }

    /** @test */
    public function name_is_required_to_update_a_timer()
    {
        $this->actingAs($this->user);
        $timer = factory(Timer::class)->create(['user_id' => $this->user->id]);

        $this->put(route('timy_timers.update', ['timy_timer' => $timer->id, 'api_token' => $this->user->api_token]), ['task_id' => null])
            ->assertSessionHasErrors(['task_id']);
    }

    /** @test */
    public function it_closes_all_running_timers()
    {
        $this->actingAs($this->user);
        factory(Timer::class, 10)->create(['user_id' => $this->user->id, 'finished_at' => null]);

        $this->post(route('timy_timers.close_all', ['api_token' => $this->user->api_token]));

        $this->assertCount(10, $this->user->timers()->get());
        $this->assertCount(0, $this->user->timers()->running()->get());
    }

    /** @test */
    public function it_retunrs_last_running_timer()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);
        factory(Timer::class, 10)->create(['user_id' => $this->user->id, 'finished_at' => null]);

        $this->get(route('timy_timers.running', ['api_token' => $this->user->api_token]))
            ->assertJson(['data' => $this->user->timers()->running()->first()->toArray()]);

        $this->assertCount(10, $this->user->timers()->get());
        $this->assertCount(1, $this->user->timers()->running()->get());
    }
}
