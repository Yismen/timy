<?php

namespace Dainsys\Timy\Repositories;

use App\User;
use Dainsys\Timy\Models\Role;
use Illuminate\Support\Facades\Cache;

class RolesRepository
{
    public static function all()
    {
        return Cache::rememberForever('timy.roles', function () {
            return Role::with(['users' => function ($query) {
                return $query->orderBy('name');
            }])->get();
        });
    }

    public static function usersWithoutRole()
    {
        return Cache::rememberForever('timy.usersWithoutRole', function () {
            return User::orderBy('name')->whereDoesntHave('timy_role')->get();
        });
    }
}
