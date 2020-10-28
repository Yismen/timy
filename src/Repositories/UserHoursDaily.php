<?php

namespace Dainsys\Timy\Repositories;

class UserHoursDaily extends UserHours
{
    public static function get($user, int $many = 0)
    {
        return self::query($user)
            ->take(17)
            ->get()
            ->reverse();
    }
}
