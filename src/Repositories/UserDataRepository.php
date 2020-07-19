<?php

namespace Dainsys\Timy\Repositories;

use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Resources\UserTimerResource;

class UserDataRepository
{
    public function toArray()
    {
        return [
            "hours_today" => UserHoursToday::get(),
            'hours_last_date' => UserHoursLastDate::get(),
            'hours_payrolltd' => UserHoursPayrollTD::get(),
            'hours_last_payroll' => UserHoursLastPayroll::get(),
            'hours_daily' => UserTimerResource::collection(UserHoursDaily::get()),
        ];
    }
}
