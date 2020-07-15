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
        $this->timers()->running()->get()->each(function ($timer) {
            $timer->stop();
        });
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
        $starts = config('timy.shift.starts_at');
        $ends = config('timy.shift.ends_at');
        if (config('timy.shift.with_shift') == true) {
            if ($current < $starts || $current > $ends) {
                event(new ShiftClosed(auth()->user()));
                throw new TimerNotCreatedException(
                    "Timy Loging Time not started. Our shifts run {$starts} to {$ends}. You can contact your supervisor for more details",
                    423
                );
            }
        }
    }
}
