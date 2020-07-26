<?php

namespace Dainsys\Timy\Repositories;

use App\User;
use Carbon\Carbon;
use Dainsys\Timy\Timer;
use Illuminate\Support\Facades\DB;

class UserHoursPayrollTD extends UserHours
{
    public static function get(User $user, int $many = 0)
    {
        $result = self::getResults($user);

        return [
            'hours' => $result->sum('hours'),
            'since' => $result->min('date'),
            'to' => $result->max('date'),
        ];
    }

    protected static function getResults(User $user)
    {
        $date = now();
        $starting_date = $date->day <= 15 ? $date->copy()->startOfMonth() : Carbon::create($date->year, $date->month, 16);
        $ending_date = $date->day > 15 ? $date->copy()->endOfMonth() : Carbon::create($date->year, $date->month, 15);

        return self::query($user)
            ->whereDate('started_at', '>=', $starting_date)
            ->whereDate('started_at', '<=', $ending_date)
            ->get();
    }
}
