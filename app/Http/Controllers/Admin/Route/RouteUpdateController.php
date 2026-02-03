<?php

namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Route;
use App\Models\BoardingLocation;
use App\Models\RouteType;
use App\Models\VehicleCategory;
// サービス
use App\Services\Admin\Route\RouteUpdateService;
// リクエスト
use App\Http\Requests\Admin\Route\RouteUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class RouteUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ルート更新']);
        // ルートを取得
        $route = Route::byPk($request->route_id)->first();
        // 乗降場所を取得
        $boarding_locations = BoardingLocation::ordered()->get();
        // ルート区分を取得
        $route_types = RouteType::ordered()->get();
        // 車両種別を取得
        $vehicle_categories = VehicleCategory::ordered()->get();
        return view('admin.route.update')->with([
            'route' => $route,
            'boarding_locations' => $boarding_locations,
            'route_types' => $route_types,
            'vehicle_categories' => $vehicle_categories,
        ]);
    }

    public function update(RouteUpdateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $RouteUpdateService = new RouteUpdateService;
                // ルートを更新
                $RouteUpdateService->updateRoute($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => 'ルートを更新しました。',
        ]);
    }
}