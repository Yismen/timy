<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Repositories\UserDataRepository;
use Livewire\Component;

class UserHoursInfo extends Component
{
    public $hours_today;

    public $hours_last_date;

    public $hours_payrolltd;

    public $hours_last_payroll;

    // public $hours_daily;

    protected function getListeners()
    {
        return [
            'timerCreatedByTimerControl' => 'getInfoData',
        ];
    }

    public function mount()
    {
        $this->getInfoData();
    }

    public function render()
    {
        return view('timy::livewire.user-hours-info');
    }

    public function getInfoData()
    {
        $data = UserDataRepository::toArray(auth()->user());

        $this->hours_today = $data['hours_today'];
        $this->hours_last_date = $data['hours_last_date'];
        $this->hours_payrolltd = $data['hours_payrolltd'];
        $this->hours_last_payroll = $data['hours_last_payroll'];

        $this->dispatchBrowserEvent('timerControlUpdated');
    }
}
