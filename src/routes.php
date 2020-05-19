<?php

use Illuminate\Support\Facades\Route;

Route::middleware('bindings')->group(function () {
    Route::apiResource('timy_tasks', '\Dainsys\Timy\Controllers\TaskController');

    Route::apiResource('timy_timers', '\Dainsys\Timy\Controllers\TimerController');
});
