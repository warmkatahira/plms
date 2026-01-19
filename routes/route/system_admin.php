<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 権限 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\Role\RoleController;
// +-+-+-+-+-+-+-+- 営業所 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\Base\BaseController;
use App\Http\Controllers\SystemAdmin\Base\BaseCreateController;
use App\Http\Controllers\SystemAdmin\Base\BaseUpdateController;
// +-+-+-+-+-+-+-+- ユーザー +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\User\UserController;
use App\Http\Controllers\SystemAdmin\User\UserCreateController;
use App\Http\Controllers\SystemAdmin\User\UserUpdateController;
// +-+-+-+-+-+-+-+- 勤務時間数 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\WorkingHour\WorkingHourController;
use App\Http\Controllers\SystemAdmin\WorkingHour\WorkingHourDeleteController;
use App\Http\Controllers\SystemAdmin\WorkingHour\WorkingHourCreateController;
// +-+-+-+-+-+-+-+- 操作ログ +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\OperationLog\OperationLogController;
use App\Http\Controllers\SystemAdmin\OperationLog\OperationLogDownloadController;

Route::middleware('common')->group(function (){
    Route::middleware(['system_admin_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 権限 +-+-+-+-+-+-+-+-
        Route::controller(RoleController::class)->prefix('role')->name('role.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        // +-+-+-+-+-+-+-+- 営業所 +-+-+-+-+-+-+-+-
        Route::controller(BaseController::class)->prefix('base')->name('base.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(BaseCreateController::class)->prefix('base_create')->name('base_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(BaseUpdateController::class)->prefix('base_update')->name('base_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        // +-+-+-+-+-+-+-+- ユーザー +-+-+-+-+-+-+-+-
        Route::controller(UserController::class)->prefix('user')->name('user.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(UserCreateController::class)->prefix('user_create')->name('user_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(UserUpdateController::class)->prefix('user_update')->name('user_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        // +-+-+-+-+-+-+-+- 勤務時間数 +-+-+-+-+-+-+-+-
        Route::controller(WorkingHourController::class)->prefix('working_hour')->name('working_hour.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(WorkingHourDeleteController::class)->prefix('working_hour_delete')->name('working_hour_delete.')->group(function(){
            Route::post('delete', 'delete')->name('delete');
        });
        Route::controller(WorkingHourCreateController::class)->prefix('working_hour_create')->name('working_hour_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        // +-+-+-+-+-+-+-+- 操作ログ +-+-+-+-+-+-+-+-
        Route::controller(OperationLogController::class)->prefix('operation_log')->name('operation_log.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(OperationLogDownloadController::class)->prefix('operation_log_download')->name('operation_log_download.')->group(function(){
            Route::get('download', 'download')->name('download');
        });
    });
});