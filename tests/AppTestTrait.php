<?php

namespace Dainsys\Timy\Tests;

use App\User;
use Dainsys\Timy\Models\Role;

trait AppTestTrait
{
    protected function user($attributes = [], $amount = null)
    {
        return factory(User::class, $amount)->create($attributes);
    }

    protected function timyUser()
    {
        $user =  $this->user();
        $role = Role::where('name', config('timy.roles.user'))->first(); //created at the migration
        $user->assignTimyRole($role);

        return $user;
    }

    protected function superAdminUser()
    {
        return $this->user(['email' => config('timy.super_admin_email')]);
    }

    protected function adminUser()
    {
        $user =  $this->user();
        $role = Role::where('name', config('timy.roles.admin'))->first(); //created at the migration
        $user->assignTimyRole($role);
        return $user;
    }
}
