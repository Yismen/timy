<?php

namespace Dainsys\Timy\Models\Traits;

trait UserScopesTrait
{

    public function scopeIsTimyUser($query)
    {
        return $query->with('timy_role')->whereHas('timy_role', function ($query) {
            $query->where('name', config('timy.roles.user'))
                ->orWhere('name', config('timy.roles.admin'));
        });
    }

    public function scopeIsTimyAdmin($query)
    {
        return $query
            ->with('timy_role')
            ->whereHas('timy_role', function ($query) {
                $query->where('name', config('timy.roles.super_admin'))
                    ->orWhere('name', config('timy.roles.admin'));
            })
            ->orWhere('email', config('timy.super_admin_email'));
    }

    public function scopeWithoutTeam($query)
    {
        return $query->whereDoesntHave('timy_team');
    }
}
