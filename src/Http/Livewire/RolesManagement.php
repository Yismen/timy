<?php

namespace Dainsys\Timy\Http\Livewire;

use App\User;
use Dainsys\Timy\Models\Role;
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
        return User::orderBy('name')->whereDoesntHave('timy_role')->get();
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
        $users = User::whereIn('id', $this->selected)->get();

        if ($this->selectedRole) {
            $role = Role::findOrFail($this->selectedRole);
            $users->each->assignTimyRole($role);
        } else {
            $users->each->removeTimyRole();
        }

        $this->closeForm();
        $this->emit('timyRoleUpdated');
    }

    public function closeForm()
    {
        $this->selected = [];

        $this->selectedRole = null;
    }
}
