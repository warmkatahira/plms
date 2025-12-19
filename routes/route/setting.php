<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 受注取込パターン +-+-+-+-+-+-+-+-
use App\Http\Controllers\Setting\OrderImportPattern\OrderImportPatternController;
use App\Http\Controllers\Setting\OrderImportPattern\OrderImportPatternDeleteController;
use App\Http\Controllers\Setting\OrderImportPattern\OrderImportPatternCreateController;
use App\Http\Controllers\Setting\OrderImportPattern\OrderImportPatternUpdateController;
// +-+-+-+-+-+-+-+- 出荷倉庫 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Setting\ShippingBase\ShippingBaseController;
use App\Http\Controllers\Setting\ShippingBase\ShippingBaseUpdateController;
// +-+-+-+-+-+-+-+- 倉庫別配送方法 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Setting\BaseShippingMethod\BaseShippingMethodController;
use App\Http\Controllers\Setting\BaseShippingMethod\BaseShippingMethodUpdateController;
// +-+-+-+-+-+-+-+- 荷送人 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Setting\Shipper\ShipperController;
use App\Http\Controllers\Setting\Shipper\ShipperCreateController;
use App\Http\Controllers\Setting\Shipper\ShipperUpdateController;
// +-+-+-+-+-+-+-+- 受注区分 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Setting\OrderCategory\OrderCategoryController;
use App\Http\Controllers\Setting\OrderCategory\OrderCategoryCreateController;
use App\Http\Controllers\Setting\OrderCategory\OrderCategoryUpdateController;
// +-+-+-+-+-+-+-+- 自動処理 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Setting\AutoProcess\AutoProcessController;
use App\Http\Controllers\Setting\AutoProcess\AutoProcessCreateController;
use App\Http\Controllers\Setting\AutoProcess\AutoProcessUpdateController;
use App\Http\Controllers\Setting\AutoProcess\AutoProcessDeleteController;
use App\Http\Controllers\Setting\AutoProcess\AutoProcessConditionUpdateController;

Route::middleware('common')->group(function (){
    Route::middleware(['warm_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 受注取込パターン +-+-+-+-+-+-+-+-
        Route::controller(OrderImportPatternController::class)->prefix('order_import_pattern')->name('order_import_pattern.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(OrderImportPatternDeleteController::class)->prefix('order_import_pattern_delete')->name('order_import_pattern_delete.')->group(function(){
            Route::post('delete', 'delete')->name('delete');
        });
        Route::controller(OrderImportPatternCreateController::class)->prefix('order_import_pattern_create')->name('order_import_pattern_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(OrderImportPatternUpdateController::class)->prefix('order_import_pattern_update')->name('order_import_pattern_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        // +-+-+-+-+-+-+-+- 出荷倉庫 +-+-+-+-+-+-+-+-
        Route::controller(ShippingBaseController::class)->prefix('shipping_base')->name('shipping_base.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(ShippingBaseUpdateController::class)->prefix('shipping_base_update')->name('shipping_base_update.')->group(function(){
            Route::post('update', 'update')->name('update');
        });
        // +-+-+-+-+-+-+-+- 倉庫別配送方法 +-+-+-+-+-+-+-+-
        Route::controller(BaseShippingMethodController::class)->prefix('base_shipping_method')->name('base_shipping_method.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(BaseShippingMethodUpdateController::class)->prefix('base_shipping_method_update')->name('base_shipping_method_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        // +-+-+-+-+-+-+-+- 荷送人 +-+-+-+-+-+-+-+-
        Route::controller(ShipperController::class)->prefix('shipper')->name('shipper.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(ShipperCreateController::class)->prefix('shipper_create')->name('shipper_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(ShipperUpdateController::class)->prefix('shipper_update')->name('shipper_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        // +-+-+-+-+-+-+-+- 受注区分 +-+-+-+-+-+-+-+-
        Route::controller(OrderCategoryController::class)->prefix('order_category')->name('order_category.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(OrderCategoryCreateController::class)->prefix('order_category_create')->name('order_category_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(OrderCategoryUpdateController::class)->prefix('order_category_update')->name('order_category_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        // +-+-+-+-+-+-+-+- 自動処理 +-+-+-+-+-+-+-+-
        Route::controller(AutoProcessController::class)->prefix('auto_process')->name('auto_process.')->group(function(){
            Route::get('', 'index')->name('index');
        });
        Route::controller(AutoProcessCreateController::class)->prefix('auto_process_create')->name('auto_process_create.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('create', 'create')->name('create');
        });
        Route::controller(AutoProcessUpdateController::class)->prefix('auto_process_update')->name('auto_process_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        Route::controller(AutoProcessDeleteController::class)->prefix('auto_process_delete')->name('auto_process_delete.')->group(function(){
            Route::post('delete', 'delete')->name('delete');
        });
        Route::controller(AutoProcessConditionUpdateController::class)->prefix('auto_process_condition_update')->name('auto_process_condition_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::get('ajax_validation', 'ajax_validation');
            Route::post('update', 'update')->name('update');
        });
    });
});