<?php

use Illuminate\Support\Facades\Route;

Route::middleware(
    preg_split("/[,|]+/", config('timy.midlewares.api'))
)
    ->prefix('timy/api')
    ->namespace('\Dainsys\Timy\Http\Controllers\Api')
    ->group(function () {
        Route::get('get_open_timer_hours', 'TimerController@getOpenTimersHours')->name('timy.getOpenTimersHours');
        Route::get('timers_filtered', 'TimerController@filtered')->name('timy.timers_filtered');
    });
