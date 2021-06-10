<?php

namespace Dainsys\Timy\Http\Livewire;

use App\User;
use Dainsys\Timy\Models\Role;
use Dainsys\Timy\Repositories\RolesRepository;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class RolesManagement extends Component
{
    /**
     * Ids of the users selected to be updated
     *
     * @var array
     */
    public $selected = [];
    /**
     * The id of the role selected to be assigned to the selected users
     *
     * @var var
     */
    public $selectedRole;
    /**
     * The key identifier to remove roles instead of assign them.
     *
     * @var string
     */
    public $remove_role_key = 'remove-role';
    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'selectedRole' => 'required'
    ];
    /**
     * Renders the view. Runs everytime a property is updated.
     *
     * @return View
     */
    public function render()
    {
        return view('timy::livewire.role-management', [
            'roles' => $this->getRoles(),
            'unassigned' => $this->getUnassigned()
        ]);
    }
    /**
     * Fetch the list of roles from the database.
     *
     * @return void
     */
    public function getRoles()
    {
        return RolesRepository::all();
    }
    /**
     * All users who don't have a role assigned.
     *
     * @return void
     */
    public function getUnassigned()
    {
        return RolesRepository::usersWithoutRole();
    }
    /**
     * Assign the selected role to the selected users. If the remove option was selected then unassign all selected users.
     *
     * @return void
     */
    public function updateRoles()
    {
        Cache::forget('timy.roles');
        $this->validate();
        $users = User::whereIn('id', $this->selected)->get();

        if ($this->selectedRole == $this->remove_role_key) {
            $users->each->removeTimyRole();
        } else {
            $role = Role::findOrFail($this->selectedRole);
            $users->each->assignTimyRole($role);
        }

        $this->closeForm();
        $this->emit('timyRoleUpdated');
    }
    /**
     * Close the form and clear all needed variables
     *
     * @return void
     */
    public function closeForm()
    {
        $this->selected = [];

        $this->selectedRole = null;
    }
    /**
     * Runs everytime any property is updated. Inented to check validation live.
     *
     * @param string $property
     * @return void
     */
    public function updated($property)
    {
        $this->validateOnly($property);
    }
}
