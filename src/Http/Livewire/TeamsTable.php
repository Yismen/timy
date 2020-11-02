<?php

namespace Dainsys\Timy\Http\Livewire;

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
        
        $this->users_without_team = resolve('TimyUser')->withoutTeam()->orderBy('name')->get();
    }

    public function updatedName()
    {
        $this->validate([
            'name' => 'required|unique:timy_teams,name|min:3'
        ]);
    }

    public function createTeam()
    {
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
        
        resolve('TimyUser')
            ->whereIn('id', $this->selected)
            ->get()->each->assignTimyTeam($team);
            
        $this->getData();
        // $this->selectedTeam = null;

        $this->closeForm();

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
}
