<?php

namespace Dainsys\Timy\Tests\Unit;

use Carbon\Carbon;
use Dainsys\Timy\Http\Livewire\TimerControl;
use Dainsys\Timy\Models\Disposition;
use Dainsys\Timy\Resources\TimerResource;
use Livewire\Livewire;

trait TimerControlTestsTrait
{
    /** @test */
    public function timer_control_renders()
    {
        $user = $this->adminUser();
        $this->actingAs($user);

        Livewire::test(TimerControl::class)
            ->assertViewIs('timy::livewire.timer-control')
            ->assertSet('dispositions', Disposition::orderBy('name')->get())
            ->assertNotSet('running', null)
            ->assertSet('user', $user);
    }

    /** @test */
    public function timer_control_always_starts_at_timer_if_withing_working_hours()
    {
        $user = $this->adminUser();
        $this->actingAs($user);
        $now = now()->startOfWeek(2)->setHour('10'); // Tuesday, 10:00 am
        Carbon::setTestNow($now);

        Livewire::test(TimerControl::class)
            ->assertViewIs('timy::livewire.timer-control')
            ->assertEmitted('timerCreatedByTimerControl')
            ->assertSet('running', TimerResource::make(
                $user->timers()->running()->first()
            )->jsonSerialize());

        $this->assertDatabaseHas('timy_timers', ['user_id' => $user->id, 'finished_at' => null]);
    }

    /** @test */
    public function timer_control_does_not_create_a_timer_if_outside_working_hours()
    {
        $user = $this->adminUser();
        $this->actingAs($user);
        $now = now()->endOfWeek(1)->setHour('20'); // Sunday, 08:00 pm
        Carbon::setTestNow($now);

        Livewire::test(TimerControl::class)
            ->assertViewIs('timy::livewire.timer-control')
            ->assertDispatchedBrowserEvent('showTimyAlert');

        $this->assertDatabaseMissing('timy_timers', ['user_id' => $user->id, 'finished_at' => null]);
    }
}
