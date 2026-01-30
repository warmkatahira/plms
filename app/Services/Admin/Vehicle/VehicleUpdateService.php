<?php

namespace App\Services\Admin\Vehicle;

// モデル
use App\Models\Vehicle;

class VehicleUpdateService
{
    // 車両を更新
    public function updateVehicle($request)
    {
        // 車両を取得
        $vehicle = Vehicle::byPk($request->vehicle_id)->lockForUpdate()->first();
        // 更新
        $vehicle->update([
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