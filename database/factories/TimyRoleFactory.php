<?php

use Dainsys\Timy\App\Disposition;
use Dainsys\Timy\App\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
