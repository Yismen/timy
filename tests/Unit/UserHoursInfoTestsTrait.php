<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Http\Livewire\UserHoursInfo;
use Dainsys\Timy\Repositories\UserDataRepository;
use Livewire\Livewire;

trait UserHoursInfoTestsTrait
{
    /** @test */
    public function user_hours_info_renders()
    {
        $this->actingAs($this->adminUser());
        $data = UserDataRepository::toArray(auth()->user());

        Livewire::test(UserHoursInfo::class)
            ->assertViewIs('timy::livewire.user-hours-info')
            ->assertSet('hours_today', $data['hours_today'])
            ->assertSet('hours_last_date', $data['hours_last_date'])
            ->assertSet('hours_payrolltd', $data['hours_payrolltd'])
            ->assertSet('hours_last_payroll', $data['hours_last_payroll'])
            ->assertDispatchedBrowserEvent('timerControlUpdated');
    }

    /** @test */
    public function user_hours_info_responds_to_events()
    {
        $this->actingAs($this->adminUser());
        $data = UserDataRepository::toArray(auth()->user());

        Livewire::test(UserHoursInfo::class)
            ->emit('timerCreatedByTimerControl')
            ->assertSet('hours_today', $data['hours_today'])
            ->assertSet('hours_last_date', $data['hours_last_date'])
            ->assertSet('hours_payrolltd', $data['hours_payrolltd'])
            ->assertSet('hours_last_payroll', $data['hours_last_payroll']);
    }
}
