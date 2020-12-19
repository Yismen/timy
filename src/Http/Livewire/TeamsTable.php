<?php

namespace Dainsys\Timy\Http\Livewire;

use App\User;
use Dainsys\Timy\Models\Team;
use Dainsys\Timy\Repositories\TeamsRepository;
use Livewire\Component;

class TeamsTable extends Component
{
    public $name;

    public $teams = [];

    public $users_without_team = [];

    public $selected = [];

    public $selectedTeam;

    protected $rules = [
        'name' => 'required|unique:timy_teams,name|min:3'
    ];

    protected function getListeners()
    {
        return [
            'timyRoleUpdated' => 'getData'
        ];
    }

    public function render()
    {
        return view('timy::livewire.teams-table');
    }

    public function mount()
    {
        $this->getData();
    }

    public function getData()
    {
        $this->teams = TeamsRepository::all();

        $this->users_without_team = TeamsRepository::usersWithoutTeam();
    }

    public function updatedName()
    {
        $this->validate();
    }

    public function createTeam()
    {
        $this->validate();

        Team::create(['name' => $this->name]);

        $this->name = '';

        $this->getData();
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

    public function assignTeam()
    {
        $this->validate([
            'selectedTeam' => 'required|exists:timy_teams,id'
        ]);
        $team = Team::findOrFail($this->selectedTeam);

        User::whereIn('id', $this->selected)
            ->get()->each->assignTimyTeam($team);

        $this->closeForm();

        $this->emit('timyTeamUpdated');

        $this->getData();
    }

    public function closeForm()
    {
        $this->selected = [];
    }

    public function updatedSelectedTeam()
    {
        $this->validateOnly($this->selectedTeam, [
            'selectedTeam' => 'required|exists:timy_teams,id'
        ]);
    }
}
