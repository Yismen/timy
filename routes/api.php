<?php

use Illuminate\Support\Facades\Route;

$apiMiddlewares = preg_split("/[,|]+/", config('timy.midlewares.api'));

Route::middleware($apiMiddlewares)
    ->prefix('timy/api')
    ->namespace('\Dainsys\Timy\Http\Controllers\Api')
    ->group(function () {
        Route::get('/user_left/{userId}', 'TimerController@userDisconnected')->name('timy_timers.user_left');
        Route::get('ping', 'TimerController@ping')->name('timy_ping_user');

        Route::apiResource('dispositions', 'DispositionController')->names('timy_dispositions');
    });
