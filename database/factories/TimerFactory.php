<?php

use Dainsys\Timy\Disposition;
use Dainsys\Timy\Timer;
use Faker\Generator as Faker;

$factory->define(Timer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'user_id' => factory(config('timy.models.user')),
        'disposition_id' => factory(Disposition::class),
        'started_at' => now(),
        'finished_at' => now(),
        'ip_address' => $faker->ipv4,
    ];
});
