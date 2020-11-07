<?php

namespace Dainsys\Timy\Repositories;

use App\User;
use Dainsys\Timy\Models\Team;

class TeamsRepository
{
    public static function all()
    {
        return Team::orderBy('name')
            ->with(['users' => function ($query) {
                return $query->whereHas('timy_role')
                    ->orderBy('name');
            }])->get();
    }

    public static function usersWithoutTeam()
    {
        return User::withoutTeam()
            ->whereHas('timy_role')->orderBy('name')->get();
    }
}
