<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Gate;

require_once(__DIR__ . '/api.php');

if (config('timy.with_web_routes')) {
    require_once(__DIR__ . '/web.php');
}

Broadcast::channel('Timy.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Timy.Admin', function ($user) {
    return Gate::allows(config('timy.roles.admin'));
});
