<?php

use Dainsys\Timy\App\Disposition;
use Faker\Generator as Faker;

$factory->define(Disposition::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'payable' => rand(0, 1),
        'invoiceable' => rand(0, 1)
    ];
});
