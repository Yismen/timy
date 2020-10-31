<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Events\TimerStopped;
use Dainsys\Timy\Repositories\DispositionsRepository;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Models\Timer;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class OpenTimersMonitor extends Component
{
    public $selected_to_change;

    public $dispositions;

    public $selected = [];

    public $timers = [];

    public $usersWithoutTimers = [];

    public $all = false;

    protected $rules = [
        'selected_to_change' => 'required|exists:timy_dispositions,id'
    ];

    public function mount()
    {
        TimerResource::withoutWrapping();
    }

    protected function updated($property)
    {
        $this->validateOnly($property);
    }

    public function render()
    {
        $this->dispositions = Cache::remember('timy_dispositions', now()->addMinutes(60), function () {
            return DispositionsRepository::all();
        });

        return view('timy::livewire.open-timers-monitor');
    }

    protected function getListeners()
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
        $this->validate($this->rules);
        try {
            resolve('TimyUser')::whereIn('id', $this->selected)->get()
                ->each->startTimer($this->selected_to_change);

            $this->resetSelectors();

            $this->getOpenTimers();
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showTimyAlert', ['message' => $th->getMessage()]);
        }
    }

    public function closeSelectedTimers()
    {
        try {
            $users =  resolve('TimyUser')::whereIn('id', $this->selected)->get();

            foreach ($users as $user) {
                $user->stopRunningTimers();
                $user->forgetTimyCache();

                event(new TimerStopped($user));

                $this->getOpenTimers();
            }

            $this->resetSelectors();
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showTimyAlert', ['message' => $th->getMessage()]);
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

        $this->usersWithoutTimers = resolve('TimyUser')
            ->isTimyUser()
            ->orderBy('name')
            ->whereDoesntHave('timers', function ($query) {
                return $query->running();
            })->get();
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
}
