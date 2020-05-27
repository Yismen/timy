<?php

use Illuminate\Support\Facades\Route;

$middlewres = preg_split("/[,|]+/", config('timy.midlewares'));

Route::middleware($middlewres)
    ->prefix('timy')
    ->group(function () {
        Route::get('timers/user_dashboard', '\Dainsys\Timy\Controllers\UserDashboardDataController@index')->name('timy_timers.user_dashboard');

        Route::get('timers/running', '\Dainsys\Timy\Controllers\TimerController@running')->name('timy_timers.running');
        Route::post('timers/close_all', '\Dainsys\Timy\Controllers\TimerController@closeAll')->name('timy_timers.close_all');

        Route::apiResource('dispositions', '\Dainsys\Timy\Controllers\DispositionController')->names('timy_dispositions');
        Route::apiResource('timers', '\Dainsys\Timy\Controllers\TimerController')->names('timy_timers');
        Route::get('ping', '\Dainsys\Timy\Controllers\TimerController@ping');
    });
