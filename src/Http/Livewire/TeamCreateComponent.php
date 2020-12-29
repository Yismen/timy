<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Models\Team;
use Livewire\Component;

class TeamCreateComponent extends Component
{
    public $team;

    protected function getRules(): array
    {
        return [
            'team.name' => [
                'required',
                'min:3',
                'unique:timy_teams,name,'
            ]
        ];
    }

    public function render()
    {
        return view('timy::livewire.team.create-component');
    }

    public function mount()
    {
        $this->team ??= new Team();
    }

    public function createTeam()
    {
        $this->validate();

        $team = Team::create(['name' => $this->team->name]);

        $this->emit('teamCreated', $team);

        $this->team = new Team();
    }
}
