<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Repositories\DispositionsRepository;
use Livewire\Component;

class ForcedTimerManagement extends Component
{
    public $selected = [];

    public $selectedDisposition;

    public function render()
    {
        return view('timy::livewire.forced-timer-management', [
            'users' => $this->getUsers(),
            'dispositions' => DispositionsRepository::all()
        ]);
    }

    protected function getListeners()
    {
        return [
            "echo-private:Timy.Admin,\\Dainsys\\Timy\\Events\\TimerCreatedAdmin" => 'getUsers',
        ];
    }

    public function getUsers()
    {
        return resolve('TimyUser')->orderBy('name')->with(['timers' => function ($query) {
            $query->running();
        }])->get();
    }


    public function toggleSelection($user_id)
    {
        if (in_array($user_id, (array)$this->selected)) {
            $this->selected = array_filter($this->selected, function ($value) use ($user_id) {
                return (int)$value != (int)$user_id;
            });
        } else {
            $this->selected[] = $user_id;
        }
    }

    public function updateUsers()
    {
        $this->validate([
            'selectedDisposition' => 'required|exists:timy_dispositions,id'
        ]);

        resolve('TimyUser')->whereIn('id', $this->selected)->get()
            ->each->startTimer((int)$this->selectedDisposition);
        $this->selected = [];
    }
}
