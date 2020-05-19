<?php

use Dainsys\Timy\Models\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'payable' => rand(0, 1),
        'invoiceable' => rand(0, 1)
    ];
});
