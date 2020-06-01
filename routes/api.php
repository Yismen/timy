<?php

use Illuminate\Support\Facades\Route;

$apiMiddlewares = preg_split("/[,|]+/", config('timy.midlewares.api'));

Route::middleware($apiMiddlewares)
    ->prefix('timy/api')
    ->group(function () {
        Route::get('timers/user_dashboard', '\Dainsys\Timy\Controllers\Api\UserDashboardDataController@index')->name('timy_timers.user_dashboard');

        Route::get('timers/running', '\Dainsys\Timy\Controllers\Api\TimerController@running')->name('timy_timers.running');
        Route::post('timers/close_all', '\Dainsys\Timy\Controllers\Api\TimerController@closeAll')->name('timy_timers.close_all');

        Route::apiResource('dispositions', '\Dainsys\Timy\Controllers\Api\DispositionController')->names('timy_dispositions');
        Route::apiResource('timers', '\Dainsys\Timy\Controllers\Api\TimerController')->names('timy_timers');
        Route::get('ping', '\Dainsys\Timy\Controllers\Api\TimerController@ping');

        Route::get('super_admin', '\Dainsys\Timy\Controllers\Api\SuperAdminController@index')->name('timy_super_admin');
        Route::post('assign/{user}/{role}', '\Dainsys\Timy\Controllers\Api\SuperAdminController@assign')->name('timy_assign_user_role');
        Route::delete('unassign/{user}', '\Dainsys\Timy\Controllers\Api\SuperAdminController@unAssign')->name('timy_unassign_user_role');
    });
