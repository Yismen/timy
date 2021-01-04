<?php

namespace Dainsys\Timy\Tests\Unit;

use App\User;
use Carbon\Carbon;
use Dainsys\Timy\Exceptions\ShiftEndendException;
use Dainsys\Timy\Models\Disposition;
use Dainsys\Timy\Models\Role;
use Dainsys\Timy\Models\Team;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Tests\TestCase;

class TimerTest extends TestCase
{
    /** @test */
    public function it_loads_relationships()
    {
        $timer = Timer::factory()->create();

        $timer->with(['user', 'disposition'])->first();

        $this->assertNotEmpty($timer->user);
        $this->assertNotEmpty($timer->disposition);
    }

    /** @test */
    public function scope_mine_returns_timers_only_for_auth_user()
    {
        $user = $this->user();
        $authUser = $this->user();
        $user->startForcedTimer(1);
        $authUser->startForcedTimer(1);

        $this->actingAs($authUser);

        $mine = Timer::mine()->get();

        $this->assertCount(2, Timer::get());
        $this->assertCount(1, $mine);
        $this->assertEquals($authUser->name, $mine->first()->name);
    }

    /** @test */
    public function scope_running_returns_open_timers_only()
    {
        $running = Timer::factory()->running()->create();
        $closed = Timer::factory()->closed()->create();

        $collection = Timer::running()->get();

        $this->assertCount(1, $collection);
        $this->assertEquals($running->name, $collection->first()->name);
    }

    /** @test */
    public function scope_payable_only_return_timers_where_disposition_is_payable()
    {
        $payableDispo = Disposition::factory()->payable()->create();
        $notPayableDispo = Disposition::factory()->notPayable()->create();
        $payableTimer = Timer::factory()->create(['disposition_id' => $payableDispo->id]);
        $notPayableTimer = Timer::factory()->create(['disposition_id' => $notPayableDispo->id]);

        $collection = Timer::payable()->get();

        $this->assertCount(1, $collection);
        $this->assertEquals($payableTimer->name, $collection->first()->name);
    }

    /** @test */
    public function scope_runningForTooLong_only_return_open_timers()
    {
        $minutes = config('timy.running_timers_threshold') + 10;
        $runningForTooLong = Timer::factory()->running()->create(['started_at' => now()->subMinutes($minutes)]);
        $closed = Timer::factory()->closed()->create();

        $collection = Timer::runningForTooLong()->get();

        $this->assertCount(1, $collection);
        $this->assertEquals($runningForTooLong->name, $collection->first()->name);
    }

    /** @test */
    public function scope_runningForTooLong_only_return_open_timers_if_stay_open_for_too_long()
    {
        $minutes = config('timy.running_timers_threshold') + 10;
        $runningForTooLong = Timer::factory()->running()->create(['started_at' => now()->subMinutes($minutes)]);
        $notRunningForTooLong = Timer::factory()->running()->create(['started_at' => now()->subMinutes($minutes - 20)]);

        $collection = Timer::runningForTooLong()->get();

        $this->assertCount(1, $collection);
        $this->assertEquals($runningForTooLong->name, $collection->first()->name);
    }

    /** @test */
    public function stop_metohd_closes_the_timer_and_persist_the_change()
    {
        $runningTimer = Timer::factory()->running()->create();

        $runningTimer->stop();

        $this->assertNotNull($runningTimer->finished_at);
        $this->assertDatabaseHas('timy_timers', ['finished_at' => $runningTimer->finished_at]);
    }

    /** @test */
    public function fakeStop_metohd_closes_the_timer_instance_but_does_not_persist_the_change()
    {
        $runningTimer = Timer::factory()->running()->create();

        $runningTimer->fakeStop();

        $this->assertNotNull($runningTimer->finished_at);
        $this->assertDatabaseHas('timy_timers', ['finished_at' => null]);
    }

    /** @test */
    public function fakeStop_metohd_does_not_change_current_value_if_called_on_a_closed_timer()
    {
        $when = now()->subHours(5);
        $closedTimer = Timer::factory()->closed($when)->create();
        $finished_at  = $closedTimer->finished_at;

        $closedTimer->fakeStop();

        $this->assertEquals($closedTimer->finished_at, $finished_at);
        $this->assertDatabaseHas('timy_timers', ['finished_at' => $finished_at]);
    }

    /** @test */
    public function it_returns_true_if_a_timer_is_running_for_too_long()
    {
        $when = now()->subHours(5);
        $closedTimer = Timer::factory()->closed($when)->create();
        $finished_at  = $closedTimer->finished_at;

        $closedTimer->fakeStop();

        $this->assertEquals($closedTimer->finished_at, $finished_at);
        $this->assertDatabaseHas('timy_timers', ['finished_at' => $finished_at]);
    }
}
