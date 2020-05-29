<?php

namespace Dainsys\Timy\Repositories;

use Dainsys\Timy\Timer;
use Illuminate\Support\Facades\DB;

class UserHoursToday extends UserHours
{
    public static function get(int $many = 0)
    {
        return self::query()
            ->whereDate('started_at', now())
            ->first();
    }
}
