<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Timer;
use Livewire\Component;
use Livewire\WithPagination;

class TimersTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    /**
     * TODO:
     * Listen to event for when a timer is updated.
     */
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
                ->with(['disposition', 'user'])
                ->mine()
                ->paginate(15)
        );
    }
}
