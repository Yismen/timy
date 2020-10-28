<?php

namespace Dainsys\Timy\Repositories;

class UserHoursToday extends UserHours
{
    public static function get($user, int $many = 0)
    {
        return self::query($user)
            ->whereDate('started_at', now())
            ->first();
    }
}
