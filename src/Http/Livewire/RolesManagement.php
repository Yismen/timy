<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Role;
use Livewire\Component;

class RolesManagement extends Component
{
    public $selected = [];

    public $selectedRole;

    public function render()
    {
        return view('timy::livewire.role-management', [
            'roles' => $this->getRoles(),
            'unassigned' => $this->getUnassigned()
        ]);
    }

    public function getRoles()
    {
        return Role::with(['users' => function ($query) {
            return $query->orderBy('name');
        }])->get();
    }

    public function getUnassigned()
    {
        return resolve('TimyUser')->orderBy('name')->whereDoesntHave('timy_role')->get();
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

    public function updateRoles()
    {
        $users = resolve('TimyUser')->whereIn('id', $this->selected)->get();
        if ($this->selectedRole) {
            $role = Role::findOrFail($this->selectedRole);
            $users->each->assignTimyRole($role);
            $this->selected = [];
        } else {
            $users->each->removeTimyRole();
            $this->selected = [];
        }
    }
}
