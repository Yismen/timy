<?php

namespace Dainsys\Timy\Repositories\Hours;

class TotalToday extends HoursBaseRepository
{
    public function getTotal()
    {
        return $this->baseQuery()->each->getTotalHoursForDate(now());
    }
}
