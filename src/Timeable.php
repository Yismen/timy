<?php

namespace Dainsys\Timy;

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

    public function startTimer(int $disposition_id)
    {
        $timer = $this->timers()->create([
            'name' => $this->name,
            'disposition_id' => $disposition_id,
            'started_at' => now(),
        ]);
        
        return $timer;
    }
}
