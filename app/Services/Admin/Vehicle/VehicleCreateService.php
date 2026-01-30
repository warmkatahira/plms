<?php

namespace App\Services\Admin\Vehicle;

// モデル
use App\Models\Vehicle;

class VehicleCreateService
{
    // 車両を追加
    public function createVehicle($request)
    {
        // 車両を追加
        Vehicle::create([
            'user_no'               => $request->owner,
            'vehicle_type_id'       => $request->vehicle_type_id,
            'vehicle_category_id'   => $request->vehicle_category_id,
            'vehicle_name'          => $request->vehicle_name,
            'vehicle_color'         => $request->vehicle_color,
            'vehicle_number'        => $request->vehicle_number,
            'vehicle_capacity'      => $request->vehicle_capacity,
            'vehicle_memo'          => $request->vehicle_memo,
            'is_active'             => $request->is_active,
        ]);
    }
}