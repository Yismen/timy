<?php

namespace Dainsys\Timy\Tests;

use App\User;
use Carbon\Carbon;
use Dainsys\Timy\Models\Disposition;
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
                'meta' => [],
                'links' => []
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

        $this->get(route('timy_timers.show', ['timy_timer' => $timer->path, 'api_token' => $this->user->api_token]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $timer->path,
                    'disposition_id' => $timer->disposition_id,
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
                    'disposition_id' => $timer['disposition_id'],
                ]
            ]);

        $this->assertDatabaseHas('timy_timers', [
            'user_id' => $timer['user_id'],
            'disposition_id' => $timer['disposition_id']
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

        $this->put(route('timy_timers.update', ['timy_timer' => $timer->path, 'api_token' => $this->user->api_token]), [
            'finished_at' => now()
        ])
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $timer->path
                ]
            ]);

        $this->assertDatabaseHas('timy_timers', [
            'finished_at' => now()
        ]);
    }

    /** @test */
    public function disposition_id_is_exists_to_create_a_timer()
    {
        $this->post(route('timy_timers.store', ['api_token' => $this->user->api_token]), ['disposition_id' => null])
            ->assertSessionHasErrors(['disposition_id']);
    }

    /** @test */
    public function name_is_required_to_update_a_timer()
    {
        $this->actingAs($this->user);
        $timer = factory(Timer::class)->create(['user_id' => $this->user->id]);

        $this->put(route('timy_timers.update', ['timy_timer' => $timer->path, 'api_token' => $this->user->api_token]), ['disposition_id' => null])
            ->assertSessionHasErrors(['disposition_id']);
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
        $this->actingAs($this->user);
        factory(Timer::class, 10)->create(['user_id' => $this->user->id, 'finished_at' => null]);

        $this->get(route('timy_timers.running', ['api_token' => $this->user->api_token]))
            ->assertJson(['data' => $this->user->timers()->running()->first()->toArray()]);

        $this->assertCount(10, $this->user->timers()->get());
        $this->assertCount(1, $this->user->timers()->running()->get());
    }

    /** @test */
    public function it_returns_user_payable_hours_today()
    {
        $this->actingAs($this->user);
        $this->createRangeOfTimers(factory(Disposition::class)->create(['payable' => 1])); // Create 1 payable for today
        $this->createRangeOfTimers(factory(Disposition::class)->create(['payable' => 0]), 1, now()); // Create 1 non payable for today

        $this->get(route('timy_timers.user_dashboard', ['api_token' => $this->user->api_token]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'hours_today' => [
                        'hours' => 8,
                        'date' => now()->format('Y-m-d')
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_user_hours_last_date()
    {
        $this->actingAs($this->user);
        $payable = factory(Disposition::class)->create(['payable' => 1]);
        $non_payable = factory(Disposition::class)->create(['payable' => 0]);

        $this->createRangeOfTimers($non_payable); // Create 1 non payable for today
        $this->createRangeOfTimers($payable); // Create 1 payable for today
        $this->createRangeOfTimers($non_payable, 0, now()->subDays(2)); // Create 1 non payable for today
        $this->createRangeOfTimers($payable, 0, now()->subDays(2)); // Create 1 payable for today

        $this->get(route('timy_timers.user_dashboard', ['api_token' => $this->user->api_token]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'hours_last_date' => [
                        'hours' => 8,
                        'date' => now()->subDays(2)->format('Y-m-d')
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_user_hours_payrolltd()
    {
        $this->actingAs($this->user);
        $date = now();
        $this->createTimersForPayroll($date);

        $starting_date = $date->day <= 15 ? $date->copy()->startOfMonth() : Carbon::create($date->year, $date->month, 16);
        $ending_date = $date->day > 15 ? $date->copy()->endOfMonth() : Carbon::create($date->year, $date->month, 15);

        $this->get(route('timy_timers.user_dashboard', ['api_token' => $this->user->api_token]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'hours_payrolltd' => [
                        'hours' => 16,
                        'since' => $starting_date->format('Y-m-d'),
                        'to' => $ending_date->format('Y-m-d')
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_user_hours_last_payroll()
    {
        $this->actingAs($this->user);
        $date = now()->subDays(15);
        $this->createTimersForPayroll($date);

        $starting_date = $date->day <= 15 ? $date->copy()->startOfMonth() : Carbon::create($date->year, $date->month, 16);
        $ending_date = $date->day > 15 ? $date->copy()->endOfMonth() : Carbon::create($date->year, $date->month, 15);

        $this->get(route('timy_timers.user_dashboard', ['api_token' => $this->user->api_token]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'hours_last_payroll' => [
                        'hours' => 16,
                        'since' => $starting_date->format('Y-m-d'),
                        'to' => $ending_date->format('Y-m-d')
                    ]
                ]
            ]);
    }
    /** @test */
    public function it_returns_user_hours_last_12_days()
    {
        $this->actingAs($this->user);
        $this->createRangeOfTimers(factory(Disposition::class)->create(['payable' => 1]), 20); // Create 10 records for last 10 days

        $this->get(route('timy_timers.user_dashboard', ['api_token' => $this->user->api_token]))
            ->assertOk()
            ->assertJsonCount(12, 'data.hours_daily')
            ->assertJson([
                'data' => [
                    'hours_daily' => []
                ]
            ]);
    }

    protected function createRangeOfTimers(Disposition $disposition, int $many_days = 0, Carbon $to_date = null)
    {
        $many_days = $many_days == 0 ? $many_days : $many_days - 1;
        $to_date = $to_date ?: now();
        $since = $to_date->copy()->subDays($many_days);
        $timers = [];

        foreach ($since->range($to_date) as $date) {
            $timers[] = factory(Timer::class)->create([
                'user_id' => $this->user->id,
                'disposition_id' => $disposition->id,
                'started_at' => $date->copy(),
                'finished_at' => $date->copy()->addHours(8)
            ]);
        }

        return $timers;
    }

    protected function createTimersForPayroll(Carbon $date)
    {
        $disposition = factory(Disposition::class)->create(['payable' => 1]);

        factory(Timer::class)->create([
            'user_id' => $this->user->id,
            'disposition_id' => $disposition->id,
            'started_at' => $date->copy()->startOfMonth(),
            'finished_at' => $date->copy()->startOfMonth()->addHours(8)
        ]);
        factory(Timer::class)->create([
            'user_id' => $this->user->id,
            'disposition_id' => $disposition->id,
            'started_at' => Carbon::create($date->year, $date->month, '15'),
            'finished_at' => Carbon::create($date->year, $date->month, '15')->addHours(8)
        ]);
        // last payroll (quincena)
        factory(Timer::class)->create([
            'user_id' => $this->user->id,
            'disposition_id' => $disposition->id,
            'started_at' => Carbon::create($date->year, $date->month, '16'),
            'finished_at' => Carbon::create($date->year, $date->month, '16')->addHours(8)
        ]);
        factory(Timer::class)->create([
            'user_id' => $this->user->id,
            'disposition_id' => $disposition->id,
            'started_at' => $date->copy()->endOfMonth(),
            'finished_at' => $date->copy()->endOfMonth()->addHours(8)
        ]);
    }
}
