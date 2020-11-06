<?php

use App\User;
use Dainsys\Timy\Models\Disposition;
use Dainsys\Timy\Models\Timer;
use Faker\Generator as Faker;

$factory->define(Timer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'user_id' => factory(User::class),
        'disposition_id' => factory(Disposition::class),
        'started_at' => now(),
        'finished_at' => null,
        'ip_address' => $faker->ipv4,
    ];
});
