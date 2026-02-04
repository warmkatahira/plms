<?php

namespace App\Services\Admin\Route;

// モデル
use App\Models\Route;

class RouteDeleteService
{
    // ルートを削除
    public function deleteRoute($request)
    {
        // ルートを取得
        $route = Route::byPk($request->route_id)->lockForUpdate()->firstOrFail();
        // 削除
        $route->delete();
    }
}