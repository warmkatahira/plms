<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- API履歴 +-+-+-+-+-+-+-+-
use App\Http\Controllers\API\ApiHistoryController;
use App\Http\Controllers\API\ApiHistoryDownloadController;

Route::middleware('common')->group(function (){
    // +-+-+-+-+-+-+-+- API履歴 +-+-+-+-+-+-+-+-
    Route::controller(ApiHistoryController::class)->prefix('api_history')->name('api_history.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    Route::controller(ApiHistoryDownloadController::class)->prefix('api_history_download')->name('api_history_download.')->group(function(){
        Route::get('download', 'download')->name('download');
    });
});