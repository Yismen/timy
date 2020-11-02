<?php

namespace Dainsys\Timy\Repositories;

use Dainsys\Timy\Models\Team;

class TeamsRepository
{
    public static function all()
    {
        return Team::orderBy('name')
            ->with('users')->get();
    }
}