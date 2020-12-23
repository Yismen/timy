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

    public Team $team;

    protected $rules = [
        'team.name' => 'required|unique:timy_teams,name|min:3'
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
        $this->team = new Team();
        $this->getData();
    }

    public function getData()
    {
        $this->teams = TeamsRepository::all();

        $this->users_without_team = TeamsRepository::usersWithoutTeam();
    }

    public function createTeam()
    {
        $this->validate();

        Team::create(['name' => $this->team->name]);

        $this->team->name = '';

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

        $this->getData();
    }

    public function assignTeam()
    {
        $this->validate([
            'selectedTeam' => 'required|exists:timy_teams,id'
        ]);

        $team = Team::findOrFail($this->selectedTeam);

        $users = User::whereIn('id', $this->selected)
            ->get();

        $users->each->assignTimyTeam($team);

        $this->closeForm();

        $this->getData();

        $this->emit('timyTeamUpdated');
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

    public function editTeam(int $team_id)
    {
        $this->team = Team::findOrFail($team_id);

        $this->dispatchBrowserEvent('show-edit-team-modal', $this->team);
    }

    public function updateTeam(int $team_id)
    {
        $this->validate();

        Team::findOrFail($team_id)
            ->update([
                'name' => $this->team->name
            ]);

        $this->team = new Team();

        $this->dispatchBrowserEvent('hide-edit-team-modal', $this->team);
    }
}
