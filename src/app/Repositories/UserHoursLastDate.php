<?php

namespace Dainsys\Timy\App\Repositories;

use Dainsys\Timy\App\Timer;
use Illuminate\Support\Facades\DB;

class UserHoursLastDate extends UserHours
{
    public static function get(int $many = 0)
    {
        return self::query()
            ->whereDate('started_at', '<=', now()->subDay())
            ->take(1)
            ->first();
    }
}
