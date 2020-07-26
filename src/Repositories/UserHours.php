<?php

namespace Dainsys\Timy\Repositories;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

abstract class UserHours
{
    abstract static function get(User $user, int $many = 0);

    protected static function query($user)
    {
        $self = new static();

        return $user->timers()
            ->selectRaw($self->getRawQuery())
            ->payable()
            ->groupBy('date')
            ->orderBy('date', 'desc');
    }

    protected function getRawQuery()
    {
        $common = ', DATE(started_at) as date, MIN(DATE(started_at)) as since, MAX(DATE(finished_at)) as ending';

        $raws_array = [
            'mysql' => 'SUM(TIMESTAMPDIFF(SECOND, started_at, finished_at)) / 60 / 60 as hours',
            'sqlite' => "SUM(strftime('%s',finished_at) - strftime('%s',started_at)) / 60 / 60 as hours",
            'pgsql' => "SUM(DATE_PART('second', finished_at) - DATE_PART('second', started_at)) / 60 / 60 as hours",
        ];

        $sql = Arr::get(
            $raws_array,
            DB::getDefaultConnection(),
            'SUM(DATEDIFF(SECOND, started_at, finished_at)) / 60 / 60 as hours'
        );

        return $sql . $common;
    }
}
