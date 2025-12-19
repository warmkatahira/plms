<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 商品 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Item\Item\ItemController;
use App\Http\Controllers\Item\Item\ItemUpdateController;
use App\Http\Controllers\Item\Item\ItemDeleteController;
use App\Http\Controllers\Item\Item\ItemDownloadController;
// +-+-+-+-+-+-+-+- セット商品 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Item\SetItem\SetItemController;
use App\Http\Controllers\Item\SetItem\SetItemUpdateController;
use App\Http\Controllers\Item\SetItem\SetItemDeleteController;
use App\Http\Controllers\Item\SetItem\SetItemDownloadController;
// +-+-+-+-+-+-+-+- モール商品マッピング +-+-+-+-+-+-+-+-
use App\Http\Controllers\Item\ItemMallMapping\ItemMallMappingController;
use App\Http\Controllers\Item\ItemMallMapping\ItemMallMappingDownloadController;
// +-+-+-+-+-+-+-+- 商品アップロード +-+-+-+-+-+-+-+-
use App\Http\Controllers\Item\ItemUpload\ItemUploadController;

Route::middleware('common')->group(function (){
    // +-+-+-+-+-+-+-+- 単品商品 +-+-+-+-+-+-+-+-
    Route::controller(ItemController::class)->prefix('item')->name('item.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    Route::middleware(['warm_check'])->group(function () {
        Route::controller(ItemUpdateController::class)->prefix('item_update')->name('item_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        Route::controller(ItemDeleteController::class)->prefix('item_delete')->name('item_delete.')->group(function(){
            Route::post('delete', 'delete')->name('delete');
        });
    });
    Route::controller(ItemDownloadController::class)->prefix('item_download')->name('item_download.')->group(function(){
        Route::get('download', 'download')->name('download');
    });
    // +-+-+-+-+-+-+-+- セット商品 +-+-+-+-+-+-+-+-
    Route::controller(SetItemController::class)->prefix('set_item')->name('set_item.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    Route::middleware(['warm_check'])->group(function () {
        Route::controller(SetItemUpdateController::class)->prefix('set_item_update')->name('set_item_update.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('update', 'update')->name('update');
        });
        Route::controller(SetItemDeleteController::class)->prefix('set_item_delete')->name('set_item_delete.')->group(function(){
            Route::post('delete', 'delete')->name('delete');
        });
    });
    Route::controller(SetItemDownloadController::class)->prefix('set_item_download')->name('set_item_download.')->group(function(){
        Route::get('download', 'download')->name('download');
    });
    // +-+-+-+-+-+-+-+- モール商品マッピング +-+-+-+-+-+-+-+-
    Route::controller(ItemMallMappingController::class)->prefix('item_mall_mapping')->name('item_mall_mapping.')->group(function(){
        Route::get('index_item', 'index_item')->name('index_item');
        Route::get('index_set_item', 'index_set_item')->name('index_set_item');
    });
    Route::controller(ItemMallMappingDownloadController::class)->prefix('item_mall_mapping_download')->name('item_mall_mapping_download.')->group(function(){
        Route::get('download_item', 'download_item')->name('download_item');
        Route::get('download_set_item', 'download_set_item')->name('download_set_item');
    });
    Route::middleware(['warm_check'])->group(function () {
        // +-+-+-+-+-+-+-+- 商品アップロード +-+-+-+-+-+-+-+-
        Route::controller(ItemUploadController::class)->prefix('item_upload')->name('item_upload.')->group(function(){
            Route::get('', 'index')->name('index');
            Route::post('upload', 'upload')->name('upload');
        });
    });
});