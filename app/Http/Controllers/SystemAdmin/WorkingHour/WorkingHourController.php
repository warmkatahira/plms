<?php

namespace App\Http\Controllers\SystemAdmin\WorkingHour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\WorkingHour;

class WorkingHourController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '勤務時間数']);
        // 1日あたりの時間数を取得
        $daily_working_hours = WorkingHour::getDailyWorkingHours()->get();
        // 半日あたりの時間数を取得
        $half_day_working_hours = WorkingHour::getHalfDayWorkingHours()->get();
        return view('system_admin.working_hour.index')->with([
            'daily_working_hours' => $daily_working_hours,
            'half_day_working_hours' => $half_day_working_hours,
        ]);
    }
}