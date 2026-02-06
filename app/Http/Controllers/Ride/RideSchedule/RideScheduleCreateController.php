<?php

namespace App\Http\Controllers\Ride\RideSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Vehicle;
use App\Models\User;
use App\Models\Route;
// サービス
use App\Services\Ride\RideSchedule\RideScheduleCreateService;
// リクエスト
use App\Http\Requests\Ride\RideSchedule\RideScheduleCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class RideScheduleCreateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '送迎予定追加']);
        // ルートを取得
        $route = Route::byPk($request->route_id)->with(['route_details', 'route_type', 'vehicle_category'])->first();
        // 車両を取得
        $vehicles = Vehicle::active()->ofVehicleCategory($route->vehicle_category_id)->ordered()->get();
        // ドライバーユーザーを取得
        $users = User::driverEligible()->active()->ordered()->get();
        return view('ride.ride_schedule.create')->with([
            'vehicles' => $vehicles,
            'users' => $users,
            'route' => $route,
        ]);
    }

    public function create(RideScheduleCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $RideScheduleCreateService = new RideScheduleCreateService;
                // 送迎予定を追加
                $RideScheduleCreateService->createRideSchedule($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '送迎予定を追加しました。',
        ]);
    }
}