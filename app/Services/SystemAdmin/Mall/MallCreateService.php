<?php

namespace App\Services\SystemAdmin\Mall;

// モデル
use App\Models\Mall;

class MallCreateService
{
    // モールを追加
    public function createMall($request)
    {
        // モールを追加
        $mall = Mall::create([
            'mall_name'             => $request->mall_name,
        ]);
        return $mall;
    }
}