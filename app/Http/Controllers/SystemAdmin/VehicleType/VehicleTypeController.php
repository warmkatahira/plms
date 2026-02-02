<?php

namespace App\Http\Controllers\SystemAdmin\VehicleType;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\VehicleType;
// トレイト
use App\Traits\PaginatesResultsTrait;

class VehicleTypeController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '車両区分']);
        // 車両区分を取得
        $vehicle_types = VehicleType::ordered();
        // ページネーションを実施
        $vehicle_types = $this->setPagination($vehicle_types);
        return view('system_admin.vehicle_type.index')->with([
            'vehicle_types' => $vehicle_types,
        ]);
    }
}