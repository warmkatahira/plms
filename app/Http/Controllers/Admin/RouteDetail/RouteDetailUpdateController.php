<?php

namespace App\Http\Controllers\Admin\RouteDetail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Route;
use App\Models\BoardingLocation;
// サービス
use App\Services\Admin\RouteDetail\RouteDetailUpdateService;
// リクエスト
use App\Http\Requests\Admin\RouteDetail\RouteDetailUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class RouteDetailUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ルート詳細更新']);
        // ルートを取得
        $route = Route::byPk($request->route_id)->with(['route_details.boarding_location', 'vehicle_category', 'route_type'])->first();
        // 乗降場所を取得
        $boarding_locations = BoardingLocation::ordered()->get();
        return view('admin.route_detail.update')->with([
            'route' => $route,
            'boarding_locations' => $boarding_locations,
        ]);
    }

    public function ajax_validation(RouteDetailUpdateRequest $request)
    {
        return response()->json([]);
    }

    public function update(Request $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $RouteDetailUpdateService = new RouteDetailUpdateService;
                // 既存のルート詳細を削除
                $RouteDetailUpdateService->deleteRouteDetail($request->route_id);
                // ルート詳細を追加
                $RouteDetailUpdateService->createRouteDetail($request);
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