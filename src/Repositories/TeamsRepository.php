<?php

namespace Dainsys\Timy\Repositories;

use App\User;
use Dainsys\Timy\Models\Team;
use Illuminate\Support\Facades\Cache;

class TeamsRepository
{
    public static function all()
    {
        return Cache::rememberForever('timy.teams', function () {
            return Team::orderBy('name')
                ->with(['users' => function ($query) {
                    return $query
                        ->orderBy('name')
                        ->with('timy_role')
                        ->whereHas('timy_role');
                }])->get();
        });
    }

    public static function usersWithoutTeam()
    {
        return User::withoutTeam()
            ->whereHas('timy_role')->orderBy('name')->get();
    }
}
