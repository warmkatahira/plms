<?php

namespace App\Services\Admin\Route;

// モデル
use App\Models\Route;

class RouteCreateService
{
    // ルートを追加
    public function createRoute($request)
    {
        // ルートを追加
        Route::create([
            'route_type_id'         => $request->route_type_id,
            'vehicle_category_id'   => $request->vehicle_category_id,
            'route_name'            => $request->route_name,
            'is_active'             => $request->is_active,
            'sort_order'            => $request->sort_order,
        ]);
    }
}