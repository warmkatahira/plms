<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 会社 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\Company\CompanyController;
use App\Http\Controllers\SystemAdmin\Company\CompanyCreateController;
use App\Http\Controllers\SystemAdmin\Company\CompanyUpdateController;
// +-+-+-+-+-+-+-+- 倉庫 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\Base\BaseController;
use App\Http\Controllers\SystemAdmin\Base\BaseCreateController;
use App\Http\Controllers\SystemAdmin\Base\BaseUpdateController;
// +-+-+-+-+-+-+-+- ユーザー +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\User\UserController;
use App\Http\Controllers\SystemAdmin\User\UserCreateController;
use App\Http\Controllers\SystemAdmin\User\UserUpdateController;
// +-+-+-+-+-+-+-+- 操作ログ +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\OperationLog\OperationLogController;
use App\Http\Controllers\SystemAdmin\OperationLog\OperationLogDownloadController;
// +-+-+-+-+-+-+-+- 祝日 +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\Holiday\HolidayController;
use App\Http\Controllers\SystemAdmin\Holiday\NationalHolidayController;
// +-+-+-+-+-+-+-+- モール +-+-+-+-+-+-+-+-
use App\Http\Controllers\SystemAdmin\Mall\MallController;
use App\Http\Controllers\SystemAdmin\Mall\MallCreateController;
use App\Http\Controllers\SystemAdmin\Mall\MallUpdateController;

Route::middleware('common')->group(function (){
    Route::middleware(['admin_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 会社 +-+-+-+-+-+-+-+-
        Route::controller(CompanyController::class)->prefix('company')->name('company.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(CompanyCreateController::class)->prefix('company_create')->name('company_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(CompanyUpdateController::class)->prefix('company_update')->name('company_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        // +-+-+-+-+-+-+-+- 倉庫 +-+-+-+-+-+-+-+-
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
        // +-+-+-+-+-+-+-+- 操作ログ +-+-+-+-+-+-+-+-
        Route::controller(OperationLogController::class)->prefix('operation_log')->name('operation_log.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(OperationLogDownloadController::class)->prefix('operation_log_download')->name('operation_log_download.')->group(function(){
            Route::get('download', 'download')->name('download');
        });
        // +-+-+-+-+-+-+-+- 祝日 +-+-+-+-+-+-+-+-
        Route::controller(HolidayController::class)->prefix('holiday')->name('holiday.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(NationalHolidayController::class)->prefix('national_holiday')->name('national_holiday.')->group(function(){
            Route::post('get_api', 'get_api')->name('get_api');
        });
        // +-+-+-+-+-+-+-+- モール +-+-+-+-+-+-+-+-
        Route::controller(MallController::class)->prefix('mall')->name('mall.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(MallCreateController::class)->prefix('mall_create')->name('mall_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(MallUpdateController::class)->prefix('mall_update')->name('mall_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
    });
});