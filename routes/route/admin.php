<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 取込履歴 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\Vehicle\VehicleController;

Route::middleware('common')->group(function (){
    Route::middleware(['admin_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 車両一覧 +-+-+-+-+-+-+-+-
        Route::controller(VehicleController::class)->prefix('vehicle')->name('vehicle.')->group(function(){
            Route::get('', 'index')->name('index');
        });
    });
});