<?php

namespace Dainsys\Timy\Http\Livewire;

use App\User;
use Dainsys\Timy\Repositories\DispositionsRepository;
use Livewire\Component;

class ForcedTimerManagement extends Component
{
    public array $selected = [];

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
        $this->users =  User::orderBy('name')
            ->with(['timers' => function ($query) {
                $query->running()
                    ->with('disposition');
            }])
            ->isTimyUser()
            ->get();
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function createForcedTimers()
    {
        $this->validate();

        User::whereIn('id', $this->selected)->get()
            ->each->startTimer((int)$this->selectedDisposition, ['forced' => true]);

        $this->closeForm();
    }

    public function closeForm()
    {
        $this->selectedDisposition = null;

        $this->selected = [];
    }
}
