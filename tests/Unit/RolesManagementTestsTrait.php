<?php

namespace Dainsys\Timy\Tests\Unit;

use App\User;
use Dainsys\Timy\Http\Livewire\RolesManagement;
use Dainsys\Timy\Models\Role;
use Livewire\Livewire;

trait RolesManagementTestsTrait
{
    /** @test */
    public function roles_management_renders()
    {
        Livewire::test(RolesManagement::class)
            ->assertViewIs('timy::livewire.role-management')
            ->assertViewHas('roles', Role::with(['users' => function ($query) {
                return $query->orderBy('name');
            }])->get())
            ->assertViewHas('unassigned', User::orderBy('name')->whereDoesntHave('timy_role')->get())
            ->assertSet('selected', [])
            ->assertSet('selectedRole', null);
    }

    /** @test */
    public function roles_management_closes_form_and_resets_vars()
    {
        Livewire::test(RolesManagement::class)
            ->set('selected', [1, 2, 3, 4, 5])
            ->set('selectedRole', 44)
            ->call('closeForm')
            ->assertSet('selected', [])
            ->assertSet('selectedRole', null);
    }

    /** @test */
    public function roles_management_toggles_selections()
    {
        $user = $this->user();

        Livewire::test(RolesManagement::class)
            ->assertSet('selected', [])
            ->call('toggleSelection', $user->id)
            ->assertSet('selected', [$user->id])
            ->call('toggleSelection', $user->id)
            ->assertSet('selected', [])
            ->assertSet('selectedDisposition', null);
    }

    /** @test */
    public function roles_management_assign_roles()
    {
        $users = $this->user([], 5);
        $role = factory(Role::class)->create();

        Livewire::test(RolesManagement::class)
            ->set('selected', $users->pluck('id')->toArray())
            ->set('selectedRole', $role->id)
            ->call('updateRoles')
            ->assertSet('selected', [])
            ->assertSet('selectedDisposition', null);

        $this->assertCount(5, User::whereHas('timy_role', function ($query) use ($role) {
            $query->where('id', $role->id);
        })->get());
    }

    /** @test */
    public function roles_management_removes_roles()
    {
        $role = factory(Role::class)->create();
        $users = $this->user([], 5)
            ->each->assignTimyRole($role);

        Livewire::test(RolesManagement::class)
            ->set('selected', $users->pluck('id')->toArray())
            ->set('selectedRole', null)
            ->call('updateRoles')
            ->assertSet('selected', [])
            ->assertSet('selectedDisposition', null);

        $this->assertCount(5, User::whereDoesntHave('timy_role', function ($query) use ($role) {
            $query->where('id', $role->id);
        })->get());
    }
}
