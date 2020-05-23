<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'bindings'])->group(function () {
    Route::apiResource('timy_dispositions', '\Dainsys\Timy\Controllers\DispositionController');

    Route::get('timy_timers/running', '\Dainsys\Timy\Controllers\TimerController@running')->name('timy_timers.running');
    Route::post('timy_timers/close_all', '\Dainsys\Timy\Controllers\TimerController@closeAll')->name('timy_timers.close_all');
    Route::apiResource('timy_timers', '\Dainsys\Timy\Controllers\TimerController');
});
Route::middleware('auth')->group(function () {
    Route::get('/timy_ping');
});
