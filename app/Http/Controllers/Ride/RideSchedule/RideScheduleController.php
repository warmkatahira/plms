<?php

namespace App\Http\Controllers\Ride\RideSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Ride\RideSchedule\RideScheduleSearchService;
// トレイト
use App\Traits\PaginatesResultsTrait;

class RideScheduleController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '送迎予定']);
        // インスタンス化
        $RideScheduleSearchService = new RideScheduleSearchService;
        // セッションを削除
        $RideScheduleSearchService->deleteSession();
        // セッションに検索条件を格納
        $RideScheduleSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $RideScheduleSearchService->getSearchResult();
        // ページネーションを実施
        $rides = $this->setPagination($result);
        return view('ride.ride_schedule.index')->with([
            'rides' => $rides,
        ]);
    }
}