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

    protected function getListeners(): array
    {
        return [
            'timyRoleUpdated' => 'getData',
            'teamCreated' => 'getData',
            'teamUpdated' => 'getData',
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

        return $this;
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
        $this->emitTo('timy::team-edit-component', 'wantsEditTeam', $team_id);
    }

    public function removeTeam(int $team_id)
    {
        $this->emitTo('timy::team-edit-component', 'wantsDeleteTeam', $team_id);
    }
}
