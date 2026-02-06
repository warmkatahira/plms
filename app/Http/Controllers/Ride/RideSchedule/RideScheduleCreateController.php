<?php

namespace App\Http\Controllers\Ride\RideSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\RouteType;
use App\Models\Vehicle;
use App\Models\User;
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
        // ルート区分を取得
        $route_types = RouteType::ordered()->get();
        // 車両を取得
        $vehicles = Vehicle::active()->ordered()->get();
        // ドライバーユーザーを取得
        $users = User::driverEligible()->active()->ordered()->get();
        return view('ride.ride_schedule.create')->with([
            'route_types' => $route_types,
            'vehicles' => $vehicles,
            'users' => $users,
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