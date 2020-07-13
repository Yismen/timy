<?php

namespace Dainsys\Timy;

use Illuminate\Support\Facades\Gate;

class SecureGates
{
    public static function boot()
    {
        Gate::define(config('timy.roles.super_admin'), function ($user) { //super admin gate
            return $user->email == config('timy.super_admin_email');
        });

        Gate::define(config('timy.roles.admin'), function ($user) { //admin gate
            return $user->hasTimyRole(config('timy.roles.admin')) || $user->email == config('timy.super_admin_email');
        });

        Gate::define(config('timy.roles.user'), function ($user) { //user gate
            // return true; // Any user can have it
            return $user->hasTimyRole(config('timy.roles.user'));
        });
    }
}
