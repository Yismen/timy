<?php

namespace Dainsys\Timy\Tests\Feature;

use Dainsys\Timy\Http\Livewire\TimersTable;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Tests\TestCase;
use Livewire\Livewire;

class TimersTableTest extends TestCase
{
    /** @test */
    public function timers_table_renders()
    {
        $this->actingAs($this->adminUser());

        Livewire::test(TimersTable::class)
            ->assertViewIs('timy::livewire.timers-table')
            ->assertViewHas('timers', TimerResource::collection(
                Timer::orderBy('started_at', 'desc')
                    ->with(['disposition', 'user.timy_team'])
                    ->mine()
                    ->paginate(15)
            ));
    }

    /** @test */
    public function timers_table_updates_timers()
    {
        $this->actingAs($this->adminUser());

        Livewire::test(TimersTable::class)
            ->call('getTimers')
            ->assertViewHas('timers', TimerResource::collection(
                Timer::orderBy('started_at', 'desc')
                    ->with(['disposition', 'user.timy_team'])
                    ->mine()
                    ->paginate(15)
            ));
    }

    /** @test */
    public function timers_table_responds_to_events()
    {
        $this->actingAs($this->adminUser());

        Livewire::test(TimersTable::class)
            ->emit('timerCreatedByTimerControl')
            ->assertViewHas('timers', TimerResource::collection(
                Timer::orderBy('started_at', 'desc')
                    ->with(['disposition', 'user.timy_team'])
                    ->mine()
                    ->paginate(15)
            ));
    }
}
