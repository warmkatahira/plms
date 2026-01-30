<?php

namespace App\Services\Admin\BoardingLocation;

// モデル
use App\Models\BoardingLocation;

class BoardingLocationCreateService
{
    // 車両を追加
    public function createBoardingLocation($request)
    {
        // 車両を追加
        BoardingLocation::create([
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