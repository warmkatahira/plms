<?php

namespace App\Services\Admin\BoardingLocation;

// モデル
use App\Models\BoardingLocation;

class BoardingLocationUpdateService
{
    // 乗降場所を更新
    public function updateBoardingLocation($request)
    {
        // 乗降場所を取得
        $boarding_location = BoardingLocation::byPk($request->boarding_location_id)->lockForUpdate()->first();
        // 更新
        $boarding_location->update([
            'location_name' => $request->location_name,
            'location_memo' => $request->location_memo,
            'is_active'     => $request->is_active,
            'sort_order'    => $request->sort_order,
        ]);
    }
}