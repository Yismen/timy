<?php

use Illuminate\Support\Facades\Route;

Route::middleware(
    preg_split("/[,|]+/", config('timy.midlewares.web'))
)
    ->prefix('timy')
    ->namespace('\Dainsys\Timy\Http\Controllers')
    ->group(function () {
        Route::get('user', 'DashboardController@user')->name('user_dashboard');
        Route::get('admin', 'DashboardController@admin')->name('admin_dashboard');
        Route::get('super_admin', 'DashboardController@superAdmin')->name('super_admin_dashboard');
        Route::post('admin/hours/download', 'DashboardController@hours')->name('timy_hours_download');
        Route::get('admin/profile/{user}', 'DashboardController@profile')->name('timy_user_profile');
        Route::get('ping', 'TimyUserController@ping')->name('timy_ping_user');
        Route::get('/user_left/{userId}', 'TimyUserController@userDisconnected')->name('timy_timers.user_left');
    });
