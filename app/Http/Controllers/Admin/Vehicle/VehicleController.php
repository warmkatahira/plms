<?php

namespace App\Http\Controllers\Admin\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\User;
use App\Models\Base;
// サービス
use App\Services\Admin\Vehicle\VehicleSearchService;
// トレイト
use App\Traits\PaginatesResultsTrait;

class VehicleController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '車両']);
        // インスタンス化
        $VehicleSearchService = new VehicleSearchService;
        // セッションを削除
        $VehicleSearchService->deleteSession();
        // セッションに検索条件を格納
        $VehicleSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $VehicleSearchService->getSearchResult();
        // ページネーションを実施
        $vehicles = $this->setPagination($result);
        return view('admin.vehicle.index')->with([
            'vehicles' => $vehicles,
        ]);
    }
}