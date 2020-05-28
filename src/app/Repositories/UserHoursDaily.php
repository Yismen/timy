<?php

namespace Dainsys\Timy\App\Repositories;

use Carbon\Carbon;
use Dainsys\Timy\App\Timer;
use Illuminate\Support\Facades\DB;

class UserHoursDaily extends UserHours
{
    public static function get(int $many = 0)
    {
        return self::query()
            ->take(12)
            ->get()
            ->reverse();
    }
}
