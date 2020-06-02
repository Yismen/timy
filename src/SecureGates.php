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
        
        Gate::define(config('timy.roles.admin'), function ($user) {//admin gate
            return $user->hasTimRole(config('timy.roles.admin'));
        });
        
        Gate::define(config('timy.roles.user'), function ($user) {//user gate
            return $user->hasTimRole(config('timy.roles.user'));
        });
    }
}
