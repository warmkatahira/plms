<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 車両 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\Vehicle\VehicleController;
use App\Http\Controllers\Admin\Vehicle\VehicleCreateController;
use App\Http\Controllers\Admin\Vehicle\VehicleUpdateController;

Route::middleware('common')->group(function (){
    Route::middleware(['admin_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 車両 +-+-+-+-+-+-+-+-
        Route::controller(VehicleController::class)->prefix('vehicle')->name('vehicle.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(VehicleCreateController::class)->prefix('vehicle_create')->name('vehicle_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(VehicleUpdateController::class)->prefix('vehicle_update')->name('vehicle_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
    });
});