<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 権限 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\Role\RoleController;
// +-+-+-+-+-+-+-+- ユーザー +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\User\UserController;
use App\Http\Controllers\SystemAdmin\User\UserCreateController;
use App\Http\Controllers\SystemAdmin\User\UserUpdateController;
// +-+-+-+-+-+-+-+- 操作ログ +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\OperationLog\OperationLogController;
use App\Http\Controllers\SystemAdmin\OperationLog\OperationLogDownloadController;

Route::middleware('common')->group(function (){
    Route::middleware(['system_admin_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 権限 +-+-+-+-+-+-+-+-
        Route::controller(RoleController::class)->prefix('role')->name('role.')->group(function(){
            Route::get('', 'index')->name('index');
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
        // +-+-+-+-+-+-+-+- 操作ログ +-+-+-+-+-+-+-+-
        Route::controller(OperationLogController::class)->prefix('operation_log')->name('operation_log.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(OperationLogDownloadController::class)->prefix('operation_log_download')->name('operation_log_download.')->group(function(){
            Route::get('download', 'download')->name('download');
        });
    });
});