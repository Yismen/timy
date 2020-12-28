<?php

namespace Dainsys\Timy\Http\Livewire;

use App\User;
use Dainsys\Timy\Models\Team;
use Dainsys\Timy\Repositories\TeamsRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class TeamsTable extends Component
{
    public Collection $teams;

    public $users_without_team = [];

    public $selected = [];

    public $selectedTeam;

    public $team;

    protected function getRules(): array
    {
        return [
            // 'team.id' => 'required|exists:timy_teams,id',
            'team.name' => [
                'required',
                'min:3',
                'unique:timy_teams,name,'
            ]
        ];
    }

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
        $this->team ??= new Team();

        $this->getData();
    }

    public function getData()
    {
        $this->teams = TeamsRepository::all();

        $this->users_without_team = TeamsRepository::usersWithoutTeam();

        return $this;
    }

    public function createTeam()
    {
        $this->validate();

        Team::create(['name' => $this->team->name]);

        $this->resetTeamProps()
            ->getData();
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

        $users = User::whereIn('id', $this->selected)
            ->get();

        $users->each->assignTimyTeam($team);

        $this->closeForm()
            ->getData();

        $this->emit('timyTeamUpdated');
    }

    public function closeForm()
    {
        $this->selected = [];

        return $this;
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

    public function updateTeam()
    {
        $this->validate([
            'team.id' => 'required|exists:timy_teams,id',
            'team.name' => [
                'required',
                'min:3',
                'unique:timy_teams,name,' . $this->team->id
            ]
        ]);

        $team = Team::findOrFail($this->team->id)
            ->update([
                'name' => $this->team->name
            ]);

        $this->resetTeamProps()
            ->getData()
            ->dispatchBrowserEvent('hide-edit-team-modal', $team);
    }

    public function beforeRemovingTeam(int $team_id)
    {
        $this->team = Team::findOrFail($team_id);

        $this->dispatchBrowserEvent('show-delete-team-modal', $this->team);
    }

    public function removeTeam()
    {
        $this->team->users->each->unassignTeam();

        $this->team->delete();

        $this->resetTeamProps()
            ->getData()
            ->dispatchBrowserEvent('hide-delete-team-modal');
    }

    protected function resetTeamProps()
    {
        $this->team = new Team();

        return $this;
    }
}
