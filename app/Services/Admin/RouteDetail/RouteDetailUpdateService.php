<?php

namespace App\Services\Admin\RouteDetail;

// モデル
use App\Models\Route;
use App\Models\RouteDetail;

class RouteDetailUpdateService
{
    // 既存のルート詳細を削除
    public function deleteRouteDetail($route_id)
    {
        RouteDetail::where('route_id', $route_id)->delete();
    }

    // ルート詳細を追加
    public function createRouteDetail($request)
    {
        // 各情報を変数に格納
        $route_id = $request->input('route_id');
        $boarding_location_ids = $request->input('boarding_location_id', []);
        $stop_orders = $request->input('stop_order', []);
        $departure_times = $request->input('departure_time', []);
        $arrival_times = $request->input('arrival_time', []);
        // 情報の分だけループ処理
        foreach($boarding_location_ids as $index => $value){
            // 追加
            RouteDetail::create([
                'route_id'              => $route_id,
                'boarding_location_id'  => $boarding_location_ids[$index],
                'stop_order'            => $stop_orders[$index],
                'departure_time'        => $departure_times[$index],
                'arrival_time'          => $arrival_times[$index],
            ]);
        }
    }
}