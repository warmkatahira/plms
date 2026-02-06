<?php

namespace App\Services\Ride\RideSchedule;

// モデル
use App\Models\RideSchedule;

class RideScheduleCreateService
{
    // 送迎予定を追加
    public function createRoute($request)
    {
        // 送迎予定を追加
        Ride::create([
            'route_type_id'         => $request->route_type_id,
            'schedule_date'         => $request->schedule_date,
            'driver_user_no'        => $request->driver_user_no,
            'use_vehicle_id'        => $request->use_vehicle_id,
            'ride_memo'             => $request->ride_memo,
            'is_active'             => $request->is_active,
        ]);
    }
}