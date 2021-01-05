<?php

namespace Dainsys\Timy\Repositories\Hours;

class PayableForDate extends HoursBaseRepository
{
    public function getTotal()
    {
        return $this->baseQuery()->each->getPayableHoursForDate($this->date);
    }
}
