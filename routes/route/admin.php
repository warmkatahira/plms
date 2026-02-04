<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 車両 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\Vehicle\VehicleController;
use App\Http\Controllers\Admin\Vehicle\VehicleCreateController;
use App\Http\Controllers\Admin\Vehicle\VehicleUpdateController;
// +-+-+-+-+-+-+-+- 乗降場所 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\BoardingLocation\BoardingLocationController;
use App\Http\Controllers\Admin\BoardingLocation\BoardingLocationCreateController;
use App\Http\Controllers\Admin\BoardingLocation\BoardingLocationUpdateController;
// +-+-+-+-+-+-+-+- ルート +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\Route\RouteController;
use App\Http\Controllers\Admin\Route\RouteCreateController;
use App\Http\Controllers\Admin\Route\RouteUpdateController;
use App\Http\Controllers\Admin\Route\RouteDeleteController;
// +-+-+-+-+-+-+-+- ルート詳細 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\RouteDetail\RouteDetailUpdateController;

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
        // +-+-+-+-+-+-+-+- 乗降場所 +-+-+-+-+-+-+-+-
        Route::controller(BoardingLocationController::class)->prefix('boarding_location')->name('boarding_location.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(BoardingLocationCreateController::class)->prefix('boarding_location_create')->name('boarding_location_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(BoardingLocationUpdateController::class)->prefix('boarding_location_update')->name('boarding_location_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        // +-+-+-+-+-+-+-+- ルート +-+-+-+-+-+-+-+-
        Route::controller(RouteController::class)->prefix('route')->name('route.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(RouteCreateController::class)->prefix('route_create')->name('route_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(RouteUpdateController::class)->prefix('route_update')->name('route_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        Route::controller(RouteDeleteController::class)->prefix('route_delete')->name('route_delete.')->group(function(){
            Route::post('delete', 'delete')->name('delete');
        });
        // +-+-+-+-+-+-+-+- ルート詳細 +-+-+-+-+-+-+-+-
        Route::controller(RouteDetailUpdateController::class)->prefix('route_detail_update')->name('route_detail_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('ajax_validation', 'ajax_validation');
            Route::post('update', 'update')->name('update');
        });
    });
});