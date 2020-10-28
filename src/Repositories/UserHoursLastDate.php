<?php

namespace Dainsys\Timy\Repositories;

class UserHoursLastDate extends UserHours
{
    public static function get($user, int $many = 0)
    {
        return self::query($user)
            ->whereDate('started_at', '<=', now()->subDay())
            ->take(1)
            ->first();
    }
}
