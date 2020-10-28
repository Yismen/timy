<?php

namespace Dainsys\Timy\Repositories;

use Dainsys\Timy\Resources\UserTimerResource;

class UserDataRepository
{
    public static function toArray($user)
    {
        return [
            "hours_today" => UserHoursToday::get($user),
            'hours_last_date' => UserHoursLastDate::get($user),
            'hours_payrolltd' => UserHoursPayrollTD::get($user),
            'hours_last_payroll' => UserHoursLastPayroll::get($user),
            'hours_daily' => UserTimerResource::collection(UserHoursDaily::get($user)),
        ];
    }
}
