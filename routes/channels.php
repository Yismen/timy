<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Gate;

Broadcast::channel('Timy.User.{id}', function ($user, $id) {
    return (int) $user->id == (int) $id;
});

Broadcast::channel('Timy.Admin', function ($user) {
    return Gate::allows(config('timy.roles.admin'));
});
