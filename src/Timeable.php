<?php

namespace Dainsys\Timy;

use Carbon\Carbon;
use Dainsys\Timy\Events\ShiftClosed;
use Dainsys\Timy\Exceptions\TimerNotCreatedException;
use Dainsys\Timy\Role;
use Dainsys\Timy\Timer;

trait Timeable
{
    public function timers()
    {
        return $this->hasMany(Timer::class);
    }

    public function timy_role()
    {
        return $this->belongsTo(Role::class, 'timy_role_id');
    }

    public function hasTimyRole($role)
    {
        return strtolower($role) == strtolower(optional($this->timy_role)->name);
    }

    public function isTimySuperAdmin()
    {
        return $this->timy_role && $this->timy_role->name == config('timy.roles.super_admin');
    }

    public function assignTimyRole(Role $role)
    {
        $this->timy_role_id = $role->id;
        $this->save();
    }

    public function removeTimyRole()
    {
        $this->timy_role_id = null;
        $this->save();
    }

    public function stopRunningTimers()
    {
        $this->timers()->running()->get()->each->stop();
    }

    public function startTimer(int $disposition_id)
    {
        $now = now();

        $this->protectAgainstTimersOutsideShift($now);

        return $this->timers()->create([
            'name' => $this->name,
            'disposition_id' => $disposition_id,
            'started_at' => $now,
        ]);
    }

    public function protectAgainstTimersOutsideShift(Carbon $now)
    {
        $current = $now->copy()->format('H:i');
        $day = $now->copy()->format('D');
        $shift = config('timy.shift');
        $starts = $shift['starts_at'];
        $ends = $shift['ends_at'];
        $workingDays = $shift['working_days'];

        if ($shift['with_shift'] == true) {
            if ($current < $starts || $current > $ends || !in_array($day, $workingDays)) {
                throw new TimerNotCreatedException(
                    "Timer not registered. Our shift runs from {$starts} to {$ends} on days " . join(", ", $workingDays) . ". You can contact your supervisor for more details",
                    423
                );
            }
        }
    }
}
