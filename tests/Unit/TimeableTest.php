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

class TimeableTest extends TestCase
{
    /** @test */
    public function it_loads_relationships()
    {
        $this->user();
        $user = User::with(['timers', 'timy_role', 'timy_team'])->first();

        $this->assertEmpty($user->timers);
        $this->assertEmpty($user->timy_role);
        $this->assertEmpty($user->timy_team);
    }

    /** @test */
    public function it_starts_a_forced_timer()
    {
        $disposition = factory(Disposition::class)->create();
        $user = $this->user();
        $user->startTimer($disposition->id, ['forced' => true]);

        $this->assertCount(1, Timer::running()->get());
        $this->assertCount(1, $user->timers()->running()->get());
    }

    /** @test */
    public function it_stops_all_running_timers()
    {
        $disposition = factory(Disposition::class)->create();
        $user = $this->user();
        $user->startTimer($disposition->id, ['forced' => true]);
        $user->stopRunningTimers();

        $this->assertCount(0, Timer::running()->get());
        $this->assertCount(0, $user->timers()->running()->get());
    }

    /** @test */
    public function it_assigns_a_timy_role()
    {
        $role = factory(Role::class)->create();
        $user = $this->user();
        $user->assignTimyRole($role);

        $this->assertEquals($user->timy_role_id, $role->id);
    }

    /** @test */
    public function it_removes_a_timy_role()
    {
        $user = $this->user();
        $user->removeTimyRole();

        $this->assertEquals($user->timy_role_id, null);
    }

    /** @test */
    public function it_assigns_a_timy_team()
    {
        $team = factory(Team::class)->create();
        $user = $this->user();
        $user->assignTimyTeam($team);

        $this->assertEquals($user->timy_team_id, $team->id);
    }

    /** @test */
    public function timeable_creates_a_timer_if_withing_shift()
    {
        $disposition = factory(Disposition::class)->create();
        $user = $this->user();
        $now = now()->startOfWeek(2)->setHour('10'); // Tuesday, 10:00 am
        Carbon::setTestNow($now);

        $user->startTimer($disposition->id);

        $this->assertDatabaseHas('timy_timers', ['user_id' => $user->id, 'finished_at' => null]);
    }

    /** @test */
    public function timeable_creates_throw_exception_if_outside_shift_and_does_not_create_the_timer()
    {
        $disposition = factory(Disposition::class)->create();
        $user = $this->user();
        $now = now()->endOfWeek(1)->setHour('20'); // Tuesday, 10:00 am
        Carbon::setTestNow($now);

        $this->expectException(ShiftEndendException::class);

        $user->startTimer($disposition->id);

        $this->assertDatabaseMissing('timy_timers', ['user_id' => $user->id, 'finished_at' => null]);
    }
}
