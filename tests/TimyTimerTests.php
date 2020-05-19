<?php

namespace Dainsys\Timy\Tests;

use App\User;
use Dainsys\Timy\Models\Timer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class TimyTimerTests extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_only_see_own_timers()
    {
        $user = factory(config('timy.models.user'))->create();
        $user2 = factory(config('timy.models.user'))->create();
        $this->actingAs($user);

        factory(Timer::class, 5)->create(['user_id' => $user->id]);
        factory(Timer::class, 5)->create(['user_id' => $user2->id]);

        $this->get('/timy_timers')
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'user_id' => $user->id
                    ]
                ],
                'to' => 5,
                'per_page' => 50
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        'user_id' => $user2->id
                    ]
                ]
            ]);
    }

    /** @test */
    public function user_can_see_a_single_timers()
    {
        $user = factory(config('timy.models.user'))->create();
        $this->actingAs($user);
        $timer = factory(Timer::class)->create(['user_id' => $user->id]);

        $this->get(route('timy_timers.show', $timer->id))
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
        $user = factory(config('timy.models.user'))->create();
        $this->actingAs($user);
        $timer = factory(Timer::class)->make([
            'user_id' => $user->id,
            'name' => $user->name
        ])->toArray();

        $this->post(route('timy_timers.store'), $timer)
            ->assertOk()
            ->assertJson([
                'data' => $timer
            ]);

        $this->assertDatabaseHas('timy_timers', [
            'user_id' => $timer['user_id'],
            'task_id' => $timer['task_id'],
        ]);
    }

    /** @test */
    public function a_timer_can_have_only_one_timer_running()
    {
        $user = factory(config('timy.models.user'))->create();
        $this->actingAs($user);
        factory(Timer::class, 5)->create(['user_id' => $user->id, 'finished_at' => null]);
        $timer = factory(Timer::class)->make(['user_id' => $user->id, 'finished_at' => null])->toArray();

        $this->post(route('timy_timers.store'), $timer);

        $this->assertCount(6, $user->timers()->get());
        $this->assertCount(1, $user->timers()->running()->get());
    }

    /** @test */
    public function a_timer_can_be_updated()
    {
        $user = factory(config('timy.models.user'))->create();
        $this->actingAs($user);
        $timer = factory(Timer::class)->create(['user_id' => $user->id, 'finished_at' => null]);

        $this->put(route('timy_timers.update', $timer->id), [
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
        $this->post(route('timy_timers.store'), ['task_id' => null])
            ->assertSessionHasErrors(['task_id']);
    }

    /** @test */
    public function name_is_required_to_update_a_timer()
    {
        $user = factory(config('timy.models.user'))->create();
        $this->actingAs($user);
        $timer = factory(Timer::class)->create(['user_id' => $user->id]);

        $this->put(route('timy_timers.update', $timer->id), ['task_id' => null])
            ->assertSessionHasErrors(['task_id']);
    }
}
