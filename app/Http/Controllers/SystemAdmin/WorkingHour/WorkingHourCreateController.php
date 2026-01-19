<?php

namespace App\Http\Controllers\SystemAdmin\WorkingHour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\SystemAdmin\WorkingHour\WorkingHourCreateService;
// リクエスト
use App\Http\Requests\SystemAdmin\WorkingHour\WorkingHourCreateRequest;
// 列挙
use App\Enums\WorkingHourEnum;
// その他
use Illuminate\Support\Facades\DB;

class WorkingHourCreateController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '勤務時間数追加']);
        // 勤務区分を取得
        $working_types = WorkingHourEnum::WORKING_TYPE_LIST;
        return view('system_admin.working_hour.create')->with([
            'working_types' => $working_types,
        ]);
    }

    public function create(WorkingHourCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $WorkingHourCreateService = new WorkingHourCreateService;
                // 勤務時間数を追加
                $WorkingHourCreateService->createWorkingHour($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('working_hour.index')->with([
            'alert_type' => 'success',
            'alert_message' => '勤務時間数を追加しました。',
        ]);
    }
}