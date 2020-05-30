<?php

use Illuminate\Support\Facades\Route;

$webMiddlewares = preg_split("/[,|]+/", config('timy.midlewares.web'));

Route::middleware($apiMiddlewares)
    ->prefix('timy')
    ->group(function () {
        Route::get('dashbaords/user', '\Dainsys\Timy\Controllers\DashboardController@user');
        Route::get('dashbaords/super_admin', '\Dainsys\Timy\Controllers\DashboardController@superAdmin');
    });
