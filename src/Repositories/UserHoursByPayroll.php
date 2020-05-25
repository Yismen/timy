<?php

namespace Dainsys\Timy\Repositories;

use Carbon\Carbon;
use Dainsys\Timy\Models\Timer;
use Illuminate\Support\Facades\DB;

class UserHoursByPayroll extends UserHours
{
    public static function get(int $many = 0)
    {
        return self::query()
            ->take(12)
            ->get()
            ->reverse();
    }
}
