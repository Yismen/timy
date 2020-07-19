<?php

namespace Dainsys\Timy\Repositories;

use Carbon\Carbon;
use Dainsys\Timy\Timer;
use Illuminate\Support\Facades\DB;

class UserHoursDaily extends UserHours
{
    public static function get(int $many = 0)
    {
        return self::query()
            ->take(15)
            ->get()
            ->reverse();
    }
}
