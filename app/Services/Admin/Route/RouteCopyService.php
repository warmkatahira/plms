<?php

namespace App\Services\Admin\Route;

// モデル
use App\Models\Route;
use App\Models\RouteDetail;

class RouteCopyService
{
    // ルートを複製
    public function copyRoute($request)
    {
        // 複製元となるルートを取得
        $route = Route::byPk($request->route_id)->with('route_details')->lockForUpdate()->firstOrFail();
        // ルート名のベースとなる名前を変数に格納
        $base_route_name = '(複製)' . $route->route_name;
        // ルート名を条件にカウント
        $count = Route::where('route_name', 'like', $base_route_name . '%')->count();
        // 今回つけるルート名を変数に格納
        $new_route_name = $count === 0 ? $base_route_name : $base_route_name . '(' . ($count + 1) . ')';
        // 親(routes)を複製
        $newRoute = $route->replicate();
        $newRoute->route_name = $new_route_name;
        $newRoute->created_at = now();
        $newRoute->updated_at = now();
        $newRoute->save();
        // 子(route_details)をまとめて複製
        foreach($route->route_details as $route_detail){
            $newRouteDetail = $route_detail->replicate();
            $newRouteDetail->route_id = $newRoute->route_id;
            $newRouteDetail->created_at = now();
            $newRouteDetail->updated_at = now();
            $newRouteDetail->save();
        }
    }
}