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

        // Route::get('super_admin', 'SuperAdminController@index')->name('timy_super_admin');
        // Route::post('assign/{user}/{role}', 'SuperAdminController@assign')->name('timy_assign_user_role');
        // Route::delete('unassign/{user}', 'SuperAdminController@unAssign')->name('timy_unassign_user_role');

        // Route::get('timers/running', 'TimerController@running')->name('timy_timers.running');
        // Route::post('timers/close_all', 'TimerController@closeAll')->name('timy_timers.close_all');
        // Route::apiResource('timers', 'TimerController')->names('timy_timers');

        // Route::get('admin', 'AdminController@index')->name('timy_admin');
        // Route::post('admin/update_user_timer/{user}/{disposition}', 'AdminController@store')->name('timy_admin.create_timer_forced');
        // Route::post('super_admin/create_forced_timer/{user}/{disposition}', 'SuperAdminController@createForcedTimer')->name('timy_super_admin.create_forced_timer');
    });
