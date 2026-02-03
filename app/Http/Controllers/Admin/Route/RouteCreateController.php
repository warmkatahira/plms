<?php

namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\BoardingLocation;
use App\Models\RouteType;
use App\Models\VehicleCategory;
// サービス
use App\Services\Admin\Route\RouteCreateService;
// リクエスト
use App\Http\Requests\Admin\Route\RouteCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class RouteCreateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ルート追加']);
        // 乗降場所を取得
        $boarding_locations = BoardingLocation::ordered()->get();
        // ルート区分を取得
        $route_types = RouteType::ordered()->get();
        // 車両種別を取得
        $vehicle_categories = VehicleCategory::ordered()->get();
        return view('admin.route.create')->with([
            'boarding_locations' => $boarding_locations,
            'route_types' => $route_types,
            'vehicle_categories' => $vehicle_categories,
        ]);
    }

    public function create(RouteCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $RouteCreateService = new RouteCreateService;
                // ルートを追加
                $RouteCreateService->createRoute($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => 'ルートを追加しました。',
        ]);
    }
}