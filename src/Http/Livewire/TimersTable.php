<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Models\Timer;
use Livewire\Component;
use Livewire\WithPagination;

class TimersTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $user;

    protected function getListeners()
    {
        return [
            'timerCreatedByTimerControl' => 'getTimers',
            // "echo-private:Timy.User.{$this->user->id},\\Dainsys\\Timy\\Events\\TimerCreated" => 'getTimers',
            // "echo-private:Timy.User.{$this->user->id},\\Dainsys\\Timy\\Events\\TimerStopped" => 'getTimers',
        ];
    }

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function render()
    {
        return view('timy::livewire.timers-table', [
            'timers' => $this->getTimers()
        ]);
    }

    public function getTimers()
    {
        return TimerResource::collection(
            Timer::orderBy('started_at', 'desc')
                ->with(['disposition', 'user.timy_team'])
                ->mine()
                ->paginate(15)
        );
    }
}
