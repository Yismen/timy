<?php

use Illuminate\Support\Facades\Route;

$webMiddlewares = preg_split("/[,|]+/", config('timy.midlewares.web'));

Route::middleware($apiMiddlewares)
    ->prefix('timy')
    ->group(function () {
        Route::get('dashboards/user', '\Dainsys\Timy\Controllers\DashboardController@user')->name('user_dashboard');
        Route::get('dashboards/admin', '\Dainsys\Timy\Controllers\DashboardController@admin')->name('admin_dashboard');
        Route::get('dashboards/super_admin', '\Dainsys\Timy\Controllers\DashboardController@superAdmin')->name('super_admin_dashboard');
    });
