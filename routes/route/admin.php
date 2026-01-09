<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 従業員 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\Employee\EmployeeController;
use App\Http\Controllers\Admin\Employee\EmployeeDownloadController;

Route::middleware('common')->group(function (){
    Route::middleware(['base_admin_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 従業員 +-+-+-+-+-+-+-+-
        Route::controller(EmployeeController::class)->prefix('employee')->name('employee.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(EmployeeDownloadController::class)->prefix('employee_download')->name('employee_download.')->group(function(){
            Route::get('download', 'download')->name('download');
        });
    });
});