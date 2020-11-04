<?php

use Illuminate\Support\Facades\Route;

$webMiddlewares = preg_split("/[,|]+/", config('timy.midlewares.web'));

Route::middleware($apiMiddlewares)
    ->prefix('timy')
    ->namespace('\Dainsys\Timy\Http\Controllers')
    ->group(function () {
        Route::get('user', 'DashboardController@user')->name('user_dashboard');
        Route::get('admin', 'DashboardController@admin')->name('admin_dashboard');
        Route::get('super_admin', 'DashboardController@superAdmin')->name('super_admin_dashboard');
        Route::post('admin/hours/download', 'DashboardController@hours')->name('timy_hours_download');
        Route::get('admin/profile/{user}', 'DashboardController@profile')->name('timy_user_profile');
        Route::resource('disposition', 'DispositionController')->names('timy_web_disposition')
            ->except('create', 'show', 'index', 'show');
    });
