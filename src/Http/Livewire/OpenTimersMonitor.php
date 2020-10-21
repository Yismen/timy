<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Events\TimerStopped;
use Dainsys\Timy\Repositories\DispositionsRepository;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Timer;
use Livewire\Component;

class OpenTimersMonitor extends Component
{
    public $selected_to_change;

    public $dispositions;

    public $selected = [];

    public $timers;

    public $exception;

    public function mount()
    {
        TimerResource::withoutWrapping();

        $this->dispositions = DispositionsRepository::all();

        $this->timers = $this->getOpenTimers();
    }

    public function render()
    {
        return view('timy::livewire.open-timers-monitor');
    }

    public function getListeners()
    {
        return [
            "echo-private:Timy.Admin,\\Dainsys\\Timy\\Events\\TimerCreatedAdmin" => 'userChangedTimer',
            "echo-presence:Timy.Presence,leaving" => 'userDisconnected',
        ];
    }

    public function userChangedTimer()
    {
        $this->timers = $this->getOpenTimers();
    }

    public function toggleSelected($timer_id)
    {
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
        if ($this->selected_to_change) {
            try {
                resolve('TimyUser')::whereIn('id', $this->selected)->get()
                    ->each->startTimer($this->selected_to_change);

                $this->selected = [];
                $this->selected_to_change = '';

                $this->timers = $this->getOpenTimers();
            } catch (\Throwable $th) {
                $this->exception = $th->getMessage();
            }
        }
    }

    public function closeSelectedTimers()
    {
        try {
            $users =  resolve('TimyUser')::whereIn('id', $this->selected)->get();

            foreach ($users as $user) {
                $user->stopRunningTimers($this->selected_to_change);

                event(new TimerStopped($user));
                $this->timers = $this->getOpenTimers();
            }

            $this->selected = [];
            $this->selected_to_change = '';
        } catch (\Throwable $th) {
            $this->exception = $th->getMessage();
        }
    }

    protected function getOpenTimers()
    {
        return TimerResource::collection(
            Timer::with(['user', 'disposition'])
                ->running()
                ->orderBy('disposition_id')
                ->orderBy('name')
                ->get()
        )->jsonSerialize();
    }

    public function userDisconnected($users)
    {
        dump($users);
        // resolve('TimyUser')->find($user['id'])->stopRunningTimers();
    }
}
