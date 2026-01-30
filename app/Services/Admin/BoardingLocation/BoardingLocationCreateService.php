<?php

namespace App\Services\Admin\BoardingLocation;

// モデル
use App\Models\BoardingLocation;

class BoardingLocationCreateService
{
    // 乗降場所を追加
    public function createBoardingLocation($request)
    {
        // 乗降場所を追加
        BoardingLocation::create([
            'location_name' => $request->location_name,
            'location_memo' => $request->location_memo,
            'is_active'     => $request->is_active,
            'sort_order'    => $request->sort_order,
        ]);
    }
}