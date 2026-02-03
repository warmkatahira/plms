<?php

namespace App\Services\Admin\Route;

// モデル
use App\Models\Route;

class RouteUpdateService
{
    // ルートを更新
    public function updateRoute($request)
    {
        // ルートを取得
        $route = Route::byPk($request->route_id)->lockForUpdate()->first();
        // 更新
        $route->update([
            'route_type_id'         => $request->route_type_id,
            'vehicle_category_id'   => $request->vehicle_category_id,
            'route_name'            => $request->route_name,
            'is_active'             => $request->is_active,
            'sort_order'            => $request->sort_order,
        ]);
    }
}