<?php

namespace App\Http\Controllers\Ride\RideSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Ride;
use App\Models\RouteType;
use App\Models\Vehicle;
use App\Models\User;
// サービス
use App\Services\Ride\RideSchedule\RideScheduleUpdateService;
// リクエスト
use App\Http\Requests\Ride\RideSchedule\RideScheduleUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class RideScheduleUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '送迎予定更新']);
        // 送迎予定を取得
        $ride = Ride::byPk($request->ride_id)->first();
        // ルート区分を取得
        $route_types = RouteType::ordered()->get();
        // 車両を取得
        $vehicles = Vehicle::active()->ordered()->get();
        // ドライバーユーザーを取得
        $users = User::driverEligible()->active()->ordered()->get();
        return view('ride.ride_schedule.update')->with([
            'ride' => $ride,
            'route_types' => $route_types,
            'vehicles' => $vehicles,
            'users' => $users,
        ]);
    }

    public function update(RideScheduleUpdateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $RideScheduleUpdateService = new RideScheduleUpdateService;
                // 送迎予定を更新
                $RideScheduleUpdateService->updateRideSchedule($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '送迎予定を更新しました。',
        ]);
    }
}