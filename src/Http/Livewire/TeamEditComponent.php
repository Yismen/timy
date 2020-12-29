<?php

namespace Dainsys\Timy\Http\Livewire;

use App\User;
use Dainsys\Timy\Models\Team;
use Dainsys\Timy\Repositories\TeamsRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class TeamEditComponent extends Component
{
    public Team $team;

    protected function getRules(): array
    {
        return [
            // 'team.id' => 'required|exists:timy_teams,id',
            'team.name' => [
                'required',
                'min:3',
                'unique:timy_teams,name'
            ]
        ];
    }

    protected function getListeners(): array
    {
        return [
            'wantsEditTeam' => 'editTeam',
            'wantsDeleteTeam' => 'beforeRemovingTeam',
        ];
    }

    public function render()
    {
        return view('timy::livewire.team.edit-component');
    }

    public function mount()
    {
        $this->team ??= new Team();
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
            ->dispatchBrowserEvent('hide-edit-team-modal', $team);

        $this->emit('teamUpdated', $team);
    }

    public function beforeRemovingTeam(int $team_id)
    {
        $this->team = Team::findOrFail($team_id);

        $this->dispatchBrowserEvent('show-delete-team-modal', $this->team);
    }

    public function removeTeam()
    {
        $this->team
            ->users
            ->each->unassignTeam();

        $this->team->delete();

        $this->emit('teamUpdated', $this->team);

        $this->resetTeamProps()
            ->dispatchBrowserEvent('hide-delete-team-modal');
    }

    protected function resetTeamProps()
    {
        $this->team = new Team();

        return $this;
    }
}
