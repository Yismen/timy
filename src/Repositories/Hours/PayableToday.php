<?php

namespace Dainsys\Timy\Repositories\Hours;

use App\User;

class PayableToday extends HoursBaseRepository
{
    public function getTotal()
    {
        return $this->baseQuery()->each->getPayableHoursForDate(now());
    }
}
