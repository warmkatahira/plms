<?php

namespace App\Http\Controllers\SystemAdmin\VehicleCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\VehicleCategory;
// トレイト
use App\Traits\PaginatesResultsTrait;

class VehicleCategoryController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '車両種別']);
        // 車両種別を取得
        $vehicle_categories = VehicleCategory::ordered();
        // ページネーションを実施
        $vehicle_categories = $this->setPagination($vehicle_categories);
        return view('system_admin.vehicle_category.index')->with([
            'vehicle_categories' => $vehicle_categories,
        ]);
    }
}