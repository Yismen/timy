<?php

namespace Dainsys\Timy\Repositories\Hours;

use App\User;
use Carbon\Carbon;

class TotalForDate extends HoursBaseRepository
{

    public function getTotal()
    {
        return $this->baseQuery()->each->getTotalHoursForDate($this->date);
    }
}
