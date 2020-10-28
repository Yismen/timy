<?php

namespace Dainsys\Timy\Repositories;

use Carbon\Carbon;

class UserHoursPayrollTD extends UserHours
{
    public static function get($user, int $many = 0)
    {
        $result = self::getResults($user);

        return [
            'hours' => $result->sum('hours'),
            'since' => $result->min('date'),
            'to' => $result->max('date'),
        ];
    }

    protected static function getResults($user)
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
