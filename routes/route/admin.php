<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 従業員一覧 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\Employee\EmployeeController;
// +-+-+-+-+-+-+-+- 従業員ダウンロード +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\Employee\EmployeeDownloadController;
// +-+-+-+-+-+-+-+- 従業員追加 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\Employee\EmployeeCreateController;
// +-+-+-+-+-+-+-+- 従業員更新 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\Employee\EmployeeUpdateController;
// +-+-+-+-+-+-+-+- 義務情報更新 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\StatutoryLeave\StatutoryLeaveUpdateController;
// +-+-+-+-+-+-+-+- 有給情報更新 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\PaidLeave\PaidLeaveUpdateController;
// +-+-+-+-+-+-+-+- その他情報更新 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\Other\OtherUpdateController;
// +-+-+-+-+-+-+-+- 取込履歴 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Admin\ImportHistory\ImportHistoryController;

Route::middleware('common')->group(function (){
    Route::middleware(['base_admin_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 従業員一覧 +-+-+-+-+-+-+-+-
        Route::controller(EmployeeController::class)->prefix('employee')->name('employee.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        // +-+-+-+-+-+-+-+- 従業員ダウンロード +-+-+-+-+-+-+-+-
        Route::controller(EmployeeDownloadController::class)->prefix('employee_download')->name('employee_download.')->group(function(){
            Route::get('download', 'download')->name('download');
        });
        Route::middleware(['admin_check'])->group(function () {
            // +-+-+-+-+-+-+-+- 従業員追加 +-+-+-+-+-+-+-+-
            Route::controller(EmployeeCreateController::class)->prefix('employee_create')->name('employee_create.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('create', 'create')->name('create');
                Route::post('import', 'import')->name('import');
            });
            // +-+-+-+-+-+-+-+- 従業員更新 +-+-+-+-+-+-+-+-
            Route::controller(EmployeeUpdateController::class)->prefix('employee_update')->name('employee_update.')->group(function(){
                Route::get('', 'index')->name('index');
                Route::post('update', 'update')->name('update');
            });
            // +-+-+-+-+-+-+-+- 義務情報更新 +-+-+-+-+-+-+-+-
            Route::controller(StatutoryLeaveUpdateController::class)->prefix('statutory_leave_update')->name('statutory_leave_update.')->group(function(){
                Route::post('import', 'import')->name('import');
            });
            // +-+-+-+-+-+-+-+- 有給情報更新 +-+-+-+-+-+-+-+-
            Route::controller(PaidLeaveUpdateController::class)->prefix('paid_leave_update')->name('paid_leave_update.')->group(function(){
                Route::post('import', 'import')->name('import');
            });
            // +-+-+-+-+-+-+-+- その他情報更新 +-+-+-+-+-+-+-+-
            Route::controller(OtherUpdateController::class)->prefix('other_update')->name('other_update.')->group(function(){
                Route::post('import', 'import')->name('import');
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