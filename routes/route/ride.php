<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 送迎予定 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Ride\RideSchedule\RideScheduleController;
use App\Http\Controllers\Ride\RideSchedule\RideScheduleCreateController;
use App\Http\Controllers\Ride\RideSchedule\RideScheduleUpdateController;

Route::middleware('common')->group(function (){
    Route::middleware(['system_admin_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 送迎予定 +-+-+-+-+-+-+-+-
        Route::controller(RideScheduleController::class)->prefix('ride_schedule')->name('ride_schedule.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(RideScheduleCreateController::class)->prefix('ride_schedule_create')->name('ride_schedule_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(RideScheduleUpdateController::class)->prefix('ride_schedule_update')->name('ride_schedule_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
    });
});