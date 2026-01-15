<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 従業員 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\Employee\EmployeeController;
use App\Http\Controllers\Admin\Employee\EmployeeDownloadController;
use App\Http\Controllers\Admin\Employee\EmployeeCreateController;
use App\Http\Controllers\Admin\Employee\EmployeeUpdateController;
// +-+-+-+-+-+-+-+- 取込履歴 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\ImportHistory\ImportHistoryController;

Route::middleware('common')->group(function (){
    Route::middleware(['base_admin_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 従業員 +-+-+-+-+-+-+-+-
        Route::controller(EmployeeController::class)->prefix('employee')->name('employee.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(EmployeeDownloadController::class)->prefix('employee_download')->name('employee_download.')->group(function(){
            Route::get('download', 'download')->name('download');
        });
        Route::middleware(['admin_check'])->group(function () {
            Route::controller(EmployeeCreateController::class)->prefix('employee_create')->name('employee_create.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('create', 'create')->name('create');
                Route::post('import', 'import')->name('import');
            });
            Route::controller(EmployeeUpdateController::class)->prefix('employee_update')->name('employee_update.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('update', 'update')->name('update');
            });
        });
    });
    Route::middleware(['admin_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 取込履歴 +-+-+-+-+-+-+-+-
        Route::controller(ImportHistoryController::class)->prefix('import_history')->name('import_history.')->group(function(){
            Route::get('', 'index')->name('index');
        });
    });
});