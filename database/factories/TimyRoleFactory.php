<?php

use Dainsys\Timy\Disposition;
use Dainsys\Timy\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
