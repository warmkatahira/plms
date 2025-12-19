<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 受注関連 +-+-+-+-+-+-+-+-
use App\Http\Controllers\API\Makeshop\MakeshopOrderController;
// +-+-+-+-+-+-+-+- 商品関連 +-+-+-+-+-+-+-+-
use App\Http\Controllers\API\Makeshop\MakeshopItemController;
// +-+-+-+-+-+-+-+- 在庫関連 +-+-+-+-+-+-+-+-
use App\Http\Controllers\API\Makeshop\MakeshopStockController;
// +-+-+-+-+-+-+-+- makeshopサンプル +-+-+-+-+-+-+-+-
use App\Http\Controllers\API\Makeshop\SampleController;
use App\Http\Controllers\API\Makeshop\UpdateProductQuantityController;

Route::middleware('common')->group(function (){
    // +-+-+-+-+-+-+-+- 受注関連 +-+-+-+-+-+-+-+-
    Route::controller(MakeshopOrderController::class)->prefix('makeshop_order')->name('makeshop_order.')->group(function(){
        Route::get('get_order', 'get_order')->name('get_order');
        Route::get('order_import', 'order_import')->name('order_import');
    });
    // +-+-+-+-+-+-+-+- 商品関連 +-+-+-+-+-+-+-+-
    Route::controller(MakeshopItemController::class)->prefix('makeshop_item')->name('makeshop_item.')->group(function(){
        Route::get('update_image', 'update_image')->name('update_image');
    });
    // +-+-+-+-+-+-+-+- 在庫関連 +-+-+-+-+-+-+-+-
    Route::controller(MakeshopStockController::class)->prefix('makeshop_stock')->name('makeshop_stock.')->group(function(){
        Route::get('update_stock', 'update_stock')->name('update_stock');
    });
    // +-+-+-+-+-+-+-+- makeshopサンプル +-+-+-+-+-+-+-+-
    Route::controller(SampleController::class)->prefix('makeshop_sample')->name('makeshop_sample.')->group(function(){
        Route::get('', 'index')->name('index');
        Route::get('getShopDeliverySetting', 'getShopDeliverySetting')->name('getShopDeliverySetting');
        Route::get('searchProduct', 'searchProduct')->name('searchProduct');
        Route::get('searchProductQuantity', 'searchProductQuantity')->name('searchProductQuantity');
        Route::get('product_sync', 'product_sync')->name('product_sync');
        Route::get('updateOrderAttribute', 'updateOrderAttribute')->name('updateOrderAttribute');
    });
    // +-+-+-+-+-+-+-+- makeshopサンプル +-+-+-+-+-+-+-+-
    Route::controller(UpdateProductQuantityController::class)->prefix('update_product_quantity')->name('update_product_quantity.')->group(function(){
        Route::get('', 'update')->name('update');
    });
});