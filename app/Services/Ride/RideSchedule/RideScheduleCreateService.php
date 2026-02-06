<?php

namespace App\Services\Ride\RideSchedule;

// モデル
use App\Models\Route;
use App\Models\Ride;
use App\Models\RideDetail;

class RideScheduleCreateService
{
    // 送迎予定を追加
    public function createRideSchedule($request)
    {
        // ルートを取得
        $route = Route::byPk($request->route_id)->with('route_details.boarding_location')->lockForUpdate()->firstOrFail();
        // 送迎日を取得
        $dates = $request->schedule_dates;
        // 送迎日の分だけループ処理
        foreach($dates as $date){
            // 送迎予定を追加
            $ride = Ride::create([
                'route_type_id'         => $route->route_type_id,
                'schedule_date'         => $date,
                'route_name'            => $route->route_name,
                'driver_user_no'        => $request->driver_user_no,
                'use_vehicle_id'        => $request->use_vehicle_id,
                'ride_memo'             => $request->ride_memo,
                'is_active'             => $request->is_active,
            ]);
            // ルート詳細をもとに、今作成したrideに紐づくride_detailsの登録データをまとめて作成する
            $rideDetails = $route->route_details->map(function ($route_detail) use ($ride) {
                return [
                    'ride_id'              => $ride->ride_id,
                    'boarding_location_id' => $route_detail->boarding_location_id,
                    'location_name'        => $route_detail->boarding_location->location_name,
                    'location_memo'        => $route_detail->boarding_location->location_memo,
                    'stop_order'           => $route_detail->stop_order,
                    'arrival_time'         => $route_detail->arrival_time,
                    'departure_time'       => $route_detail->departure_time,
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ];
            })->toArray();
            // 送迎詳細を追加
            RideDetail::insert($rideDetails);
        }
    }
}