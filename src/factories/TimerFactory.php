<?php

use Dainsys\Timy\Models\Task;
use Dainsys\Timy\Models\Timer;
use Faker\Generator as Faker;

$factory->define(Timer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'user_id' => factory(config('timy.models.user')),
        'task_id' => factory(Task::class),
        'started_at' => now()
    ];
});
