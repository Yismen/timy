<?php

namespace Dainsys\Timy\Repositories;

use Carbon\Carbon;
use Dainsys\Timy\Timer;
use Illuminate\Support\Facades\DB;

class UserHoursLastPayroll extends UserHours
{
    public static function get(int $many = 0)
    {
        $result = self::getResults();

        return [
            'hours' => $result->sum('hours'),
            'since' => $result->min('date'),
            'to' => $result->max('date'),
        ];
    }

    protected static function getResults()
    {
        $date = now()->subDays(15);

        $starting_date = $date->day <= 15 ? $date->copy()->startOfMonth() : Carbon::create($date->year, $date->month, 16);
        $ending_date = $date->day > 15 ? $date->copy()->endOfMonth() : Carbon::create($date->year, $date->month, 15);

        return self::query()
            ->whereDate('started_at', '>=', $starting_date)
            ->whereDate('started_at', '<=', $ending_date)
            ->get();
    }
}
