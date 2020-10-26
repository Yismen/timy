<?php

namespace Dainsys\Timy;

use Carbon\Carbon;
use Dainsys\Timy\Events\TimerCreated;
use Dainsys\Timy\Events\TimerCreatedAdmin;
use Dainsys\Timy\Exceptions\ShiftEndendException;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Role;
use Dainsys\Timy\Timer;
use Illuminate\Support\Facades\Cache;

trait Timeable
{
    public $cache_key = 'timy-user-last-disposition-';

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

    public function startTimer(int $disposition_id, $options = null)
    {
        $this->stopRunningTimers();

        if ($options && !is_array($options)) {
            abort(500, "Options must be an array or null!");
        }

        $now = now();

        if (!is_array($options)  || !array_key_exists('forced', $options)) {
            $this->protectAgainstTimersOutsideShift($now);
        }

        return $this->getTimerStarted($disposition_id, $now);
    }

    public function forgetTimyCache()
    {
        $cache_key = $this->cache_key . $this->id;

        Cache::forget($cache_key);
    }

    protected function getTimerStarted($disposition_id, $now)
    {
        $this->forgetTimyCache();
        $cache_key = $this->cache_key . $this->id;

        Cache::rememberForever($cache_key, function () use ($disposition_id) {
            return "$disposition_id";
        });

        $timer = $this->timers()->create([
            'name' => $this->name,
            'disposition_id' => $disposition_id,
            'started_at' => $now,
            'ip_address' => request()->ip()
        ]);

        event(new TimerCreated($this, $timer));
        event(new TimerCreatedAdmin($this, $timer));

        return TimerResource::make($timer)->jsonSerialize();
    }

    protected function protectAgainstTimersOutsideShift(Carbon $now)
    {
        $current = $now->copy()->format('H:i');
        $day = $now->copy()->format('D');
        $shift = config('timy.shift');
        $starts = $shift['starts_at'];
        $ends = $shift['ends_at'];
        $workingDays = $shift['working_days'];

        if ($shift['with_shift'] == true) {
            if ($current < $starts || $current > $ends || !in_array($day, $workingDays)) {
                $this->forgetTimyCache();

                throw new ShiftEndendException(
                    trans('timy::titles.out_of_shift'),
                    423
                );
            }
        }
    }
}
