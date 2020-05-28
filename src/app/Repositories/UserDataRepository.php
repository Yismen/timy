<?php

namespace Dainsys\Timy\App\Repositories;

use Dainsys\Timy\App\Resources\TimerResource;
use Dainsys\Timy\App\Resources\UserTimerResource;

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
            'hours_by_payrolls' => UserTimerResource::collection(UserHoursByPayroll::get()),
        ];
    }
}
