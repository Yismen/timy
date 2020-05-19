<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'bindings'])->group(function () {
    Route::apiResource('timy_tasks', '\Dainsys\Timy\Controllers\TaskController');

    Route::apiResource('timy_timers', '\Dainsys\Timy\Controllers\TimerController');
});
