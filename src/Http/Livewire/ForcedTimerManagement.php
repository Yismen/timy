<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Repositories\DispositionsRepository;
use Livewire\Component;

class ForcedTimerManagement extends Component
{
    public $selected = [];

    public $selectedDisposition;

    public $users;

    protected $rules = [
        'selectedDisposition' => 'required|exists:timy_dispositions,id'
    ];

    public function render()
    {
        return view('timy::livewire.forced-timer-management', [
            'dispositions' => DispositionsRepository::all(),
            'users' => $this->getUsers()
        ]);
    }

    protected function getListeners()
    {
        return [
            "echo-private:Timy.Admin,\\Dainsys\\Timy\\Events\\TimerCreatedAdmin" => 'getUsers',
            'timyRoleUpdated' => 'getUsers'
        ];
    }

    public function getUsers()
    {
        $this->users =  resolve('TimyUser')
            ->orderBy('name')
            ->with(['timers' => function ($query) {
                $query->running()
                    ->with('disposition');
            }])
            ->isTimyUser()
            ->get();
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

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function createForcedTimers()
    {
        $this->validate();

        resolve('TimyUser')->whereIn('id', $this->selected)->get()
            ->each->startTimer((int)$this->selectedDisposition, ['forced' => true]);

        $this->closeForm();
    }

    public function closeForm()
    {
        $this->selectedDisposition = null;

        $this->selected = [];
    }
}
