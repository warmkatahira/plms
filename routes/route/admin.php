<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 従業員 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\Employee\EmployeeController;

Route::middleware('common')->group(function (){
    Route::middleware(['base_admin_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 従業員 +-+-+-+-+-+-+-+-
        Route::controller(EmployeeController::class)->prefix('employee')->name('employee.')->group(function(){
            Route::get('', 'index')->name('index');
        });
    });
});