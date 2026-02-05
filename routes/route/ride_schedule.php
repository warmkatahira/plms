<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 送迎 +-+-+-+-+-+-+-+-
use App\Http\Controllers\RideSchedule\RideSchedule\RideScheduleController;

Route::middleware('common')->group(function (){
    Route::middleware(['system_admin_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 送迎 +-+-+-+-+-+-+-+-
        Route::controller(RideScheduleController::class)->prefix('ride_schedule')->name('ride_schedule.')->group(function(){
            Route::get('', 'index')->name('index');
        });
    });
});