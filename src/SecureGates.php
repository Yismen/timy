<?php

namespace Dainsys\Timy;

use Illuminate\Support\Facades\Gate;

class SecureGates
{
    public static function boot()
    {
        Gate::define(config('timy.super_admin.role'), function ($user) {
            return $user->email == config('timy.super_admin.email');
        });
    }
}
