<?php

namespace Dainsys\Timy\Models\Traits\Filters;

trait TimerFiltersTrait
{
    public function isRunningForTooLong()
    {
        return $this->is_payable && $this->started_at->diffInMinutes(now()) >= config('timy.running_timers_threshold');
    }
}
