<?php

namespace Dainsys\Timy\Repositories;

use App\User;
use Carbon\Carbon;
use Dainsys\Timy\Timer;
use Illuminate\Support\Facades\DB;

class UserHoursDaily extends UserHours
{
    public static function get(User $user, int $many = 0)
    {
        return self::query($user)
            ->take(17)
            ->get()
            ->reverse();
    }
}
