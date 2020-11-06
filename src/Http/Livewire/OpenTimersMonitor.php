<?php

namespace Dainsys\Timy\Http\Livewire;

use App\User;
use Dainsys\Timy\Events\TimerStopped;
use Dainsys\Timy\Repositories\DispositionsRepository;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Repositories\TimersRepository;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class OpenTimersMonitor extends Component
{
    public $selectedDisposition;

    public $dispositions;

    public $selected = [];

    public $timers = [];

    public $usersWithoutTimers = [];

    public $all = false;

    protected $rules = [
        'selectedDisposition' => 'required|exists:timy_dispositions,id',
        'selected' => 'array|required',
    ];

    public function mount()
    {
        $this->dispositions = Cache::remember('timy_dispositions', now()->addMinutes(60), function () {
            return DispositionsRepository::all();
        });

        TimerResource::withoutWrapping();
    }

    protected function updated($property)
    {
        $this->validateOnly($property);
    }

    public function render()
    {
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
            User::whereIn('id', $this->selected)->get()
                ->each->startTimer($this->selectedDisposition);

            $this->resetSelectors();

            $this->getOpenTimers();
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showTimyAlert', ['message' => $th->getMessage()]);
        }
    }

    public function closeSelectedTimers()
    {
        try {
            $users =  User::whereIn('id', $this->selected)->get();

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
            TimersRepository::all()
        )->jsonSerialize();

        $this->usersWithoutTimers = User::isTimyUser()
            ->orderBy('name')
            ->whereDoesntHave('timers', function ($query) {
                return $query->running();
            })->get();
    }

    public function resetSelectors()
    {
        $this->selected = [];
        $this->selectedDisposition = '';
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
