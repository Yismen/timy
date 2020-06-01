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
        return $this->timy_role->name == $role;
    }

    public function isTimySuperAdmin()
    {
        return $this->timy_role && $this->timy_role->name == config('timy.super_admin.role');
    }

    public function assigTimyRole(Role $role)
    {
        $this->timy_role_id = $role->id;
        $this->save();
    }

    public function removeTimyRole()
    {
        $this->timy_role_id = null;
        $this->save();
    }

    public function startTimer($disposition_id)
    {
        $this->timers()->create([
            'name' => $this->name,
            'disposition_id' => $disposition_id,
            'started_at' => now(),
        ]);
    }
}
