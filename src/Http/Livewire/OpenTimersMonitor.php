<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Events\TimerStopped;
use Dainsys\Timy\Repositories\DispositionsRepository;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Timer;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class OpenTimersMonitor extends Component
{
    public $selected_to_change;

    public $dispositions;

    public $selected = [];

    public $timers;

    public $exception;

    public $all = false;

    public function mount()
    {
        TimerResource::withoutWrapping();

        $this->dispositions = DispositionsRepository::all();

        $this->getOpenTimers();
    }

    public function render()
    {
        return view('timy::livewire.open-timers-monitor');
    }

    public function getListeners()
    {
        return [
            "echo-private:Timy.Admin,\\Dainsys\\Timy\\Events\\TimerCreatedAdmin" => 'getOpenTimers',
            "timerCreatedByTimerControl" => 'getOpenTimers',
        ];
    }

    public function toggleSelected($timer_id)
    {
        $this->all = false;
        if (!in_array($timer_id, $this->selected)) {
            $this->selected[] = $timer_id;
        } else {
            $this->selected = array_filter($this->selected, function ($value) use ($timer_id) {
                return (int)$value != (int)$timer_id;
            });
        }
    }

    public function updateSelectedTimers()
    {
        $this->validate([
            'selected_to_change' => 'required|exists:timy_dispositions,id'
        ]);

        try {
            resolve('TimyUser')::whereIn('id', $this->selected)->get()
                ->each->startTimer($this->selected_to_change);

            $this->resetSelectors();

            $this->getOpenTimers();
        } catch (\Throwable $th) {
            $this->exception = $th->getMessage();
        }
    }

    public function closeSelectedTimers()
    {
        try {
            $users =  resolve('TimyUser')::whereIn('id', $this->selected)->get();

            foreach ($users as $user) {
                $user->stopRunningTimers($this->selected_to_change);
                Cache::forget($user->cache_key . $user->id);

                event(new TimerStopped($user));

                $this->getOpenTimers();
            }

            $this->resetSelectors();
        } catch (\Throwable $th) {
            $this->exception = $th->getMessage();
        }
    }

    public function getOpenTimers()
    {
        $this->timers = TimerResource::collection(
            Timer::with(['user', 'disposition'])
                ->running()
                ->orderBy('disposition_id')
                ->orderBy('name')
                ->get()
        )->jsonSerialize();
    }

    public function resetSelectors()
    {
        $this->selected = [];
        $this->selected_to_change = '';
        $this->all = false;
    }

    public function toggleSelectAll()
    {
        $this->all = !$this->all;
        $this->selected = $this->all ?
            array_map(function ($timer) {
                return $timer['user_id'];
            }, $this->timers) :
            [];
    }

    public function cancelUpdating()
    {
    }
}
