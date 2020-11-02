<?php

namespace Dainsys\Timy\Repositories;

use Dainsys\Timy\Models\Timer;

class TimersRepository
{
    public static function all()
    {
        return Timer::with(['user.timy_team', 'disposition'])
            ->running()
            ->orderBy('disposition_id')
            ->orderBy('name')
            ->get();
    }
}