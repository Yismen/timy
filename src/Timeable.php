<?php

namespace Dainsys\Timy;

use Carbon\Carbon;
use Dainsys\Timy\Events\TimerCreated;
use Dainsys\Timy\Events\TimerCreatedAdmin;
use Dainsys\Timy\Exceptions\ShiftEndendException;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Models\Role;
use Dainsys\Timy\Models\Team;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Models\Traits\UserHoursTrait;
use Dainsys\Timy\Models\Traits\UserScopesTrait;
use Illuminate\Support\Facades\Cache;

trait Timeable
{
    use UserScopesTrait;
    use UserHoursTrait;

    public $cache_key = 'timy-user-last-disposition-';
    /**
     * Defines a User has many Timer relationship.
     *
     * @return Timer relationship
     */
    public function timers()
    {
        return $this->hasMany(Timer::class, 'user_id');
    }
    /**
     * Defines a User belongs to a Role relationship.
     *
     * @return void
     */
    public function timy_role()
    {
        return $this->belongsTo(Role::class, 'timy_role_id');
    }
    /**
     * Defines a User belongs to a Team relationship.
     *
     * @return void
     */
    public function timy_team()
    {
        return $this->belongsTo(Team::class, 'timy_team_id');
    }
    /**
     * Checks if a User has a Timy role assigned by name.
     *
     * @param [type] $role
     * @return boolean
     */
    public function hasTimyRole(string $role): bool
    {
        return strtolower($role) == strtolower(optional($this->timy_role)->name);
    }
    /**
     * Checks if a User is a Timy Super Admin.
     *
     * @return boolean
     */
    public function isTimySuperAdmin(): bool
    {
        $super_admin_email = config('timy.super_admin_email');

        return $this->email == $super_admin_email || $this->hasTimyRole(config('timy.roles.super_admin'));
    }
    /**
     * Asign the give Role instance to a User.
     *
     * @param Role $role
     * @return void
     */
    public function assignTimyRole(Role $role)
    {
        $this->timy_role_id = $role->id;
        $this->save();
    }
    /**
     * Assing the given Team instance to a User.
     *
     * @param Team $team
     * @return void
     */
    public function assignTimyTeam(Team $team)
    {
        $this->timy_team_id = $team->id;
        $this->save();
    }
    /**
     * Remove the assigned Team to the User.
     *
     * @return void
     */
    public function unassignTeam()
    {
        $this->timy_team_id = null;
        $this->save();
    }
    /**
     * Removes the assigned Role to a User.
     *
     * @return void
     */
    public function removeTimyRole()
    {
        $this->stopRunningTimers();

        $this->timy_role_id = null;
        $this->save();
    }
    /**
     * Query all running timers for the given user and runs the stop method.
     *
     * @return void
     */
    public function stopRunningTimers()
    {
        $this->timers()->running()->get()->each->stop();
    }
    /**
     * Start a new open timer for the User, but before stop any running timer.
     * This method also protect against opening timers outside of the shift windodw as defined in the config file.
     *
     * @param integer $disposition_id
     * @param [type] $options
     * @return void
     */
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
    /**
     * Start a new timer for the user, regardless of the shifts definition. See startTimer for consideration.
     *
     * @param integer $disposition_id
     * @return void
     */
    public function startForcedTimer(int $disposition_id)
    {
        return $this->startTimer($disposition_id, ['forced' => true]);
    }
    /**
     * Return a cached version of the last disposition userd by the User.
     * If there is no last dispo in che cache, it returns the disposition id defined as default in the config.
     *
     * @param [type] $user_id
     * @return void
     */
    public function getTimyCachedDispo($user_id = null)
    {
        $user_id = $user_id ?: auth()->id();

        return Cache::get($this->cache_key . $user_id, config('timy.default_disposition_id'));
    }
    /**
     * Clear the user cached disposition.
     *
     * @return void
     */
    public function forgetTimyCache()
    {
        $cache_key = $this->cache_key . $this->id;

        Cache::forget($cache_key);
    }
    /**
     * Private method to execute the logic of getting a timer started. Needed by the startTimer method.
     *
     * @param [type] $disposition_id
     * @param [type] $now
     * @return void
     */
    private function getTimerStarted($disposition_id, $now)
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
    /**
     * Provite method to hold the logic of protecting against staring timers outside of the shifts defined in the config.
     *
     * @param Carbon $now
     * @return void
     */
    private function protectAgainstTimersOutsideShift(Carbon $now)
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
                $this->stopRunningTimers();

                throw new ShiftEndendException(
                    trans('timy::titles.out_of_shift'),
                    423
                );
            }
        }
    }
}
