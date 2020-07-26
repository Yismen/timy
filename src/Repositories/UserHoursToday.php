<?php

namespace Dainsys\Timy\Repositories;

use App\User;
use Dainsys\Timy\Timer;
use Illuminate\Support\Facades\DB;

class UserHoursToday extends UserHours
{
    public static function get(User $user, int $many = 0)
    {
        return self::query($user)
            ->whereDate('started_at', now())
            ->first();
    }
}
