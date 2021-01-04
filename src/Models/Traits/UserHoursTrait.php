<?php

namespace Dainsys\Timy\Models\Traits;

use App\User;
use Carbon\Carbon;

trait UserHoursTrait
{
    /**
     * Assign the total_hours property to the current User with the sum of the hours for the given date, regardless of the payable status.
     *
     * @param Carbon $date
     * @return User
     */
    public function getTotalHoursForDate(Carbon $date): User
    {
        return $this->totalHours($payable = false, $date);
    }
    /**
     * Assign the total_hours property to the current User with the sum of the hours for the given date, regardless of the payable status.
     *
     * @return User
     */
    public function getTodayTotalHours(): User
    {
        return $this->totalHours($payable = false, now());
    }
    /**
     * Assign the total_hours property to the current User with the sum of payable hours for today.
     *
     * @return User
     */
    public function getTodayPayableHours(): User
    {
        return $this->getPayableHoursForDate(now());
    }
    /**
     * Assign the total_hours property to the current User with the sum of payable hours for the given date.
     *
     * @param Carbon $date
     * @return User
     */
    public function getPayableHoursForDate(Carbon $date): User
    {
        return $this->totalHours($payable = true, $date);
    }
    /**
     * Private method to execute the logic of assigning the total_hours property to the user instance based on the API methods.
     *
     * @param boolean $payable
     * @param Carbon $date
     * @return User
     */
    private function totalHours(bool $payable = false, Carbon $date = null): User
    {
        $query = $this->timers()->whereDate('started_at', $date ?: now());

        if ($payable) {
            $query->payable();
        }

        $query = $query->get()->each->fakeStop();

        $this->total_hours = $query->sum('total_hours');

        return $this;
    }
}
