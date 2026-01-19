<?php

namespace App\Http\Controllers\SystemAdmin\WorkingHour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\SystemAdmin\WorkingHour\WorkingHourDeleteService;
// リクエスト
use App\Http\Requests\SystemAdmin\WorkingHour\WorkingHourDeleteRequest;
// その他
use Illuminate\Support\Facades\DB;

class WorkingHourDeleteController extends Controller
{
    public function delete(WorkingHourDeleteRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $WorkingHourDeleteService = new WorkingHourDeleteService;
                // 削除可能か確認
                $WorkingHourDeleteService->checkDeletable($request);
                // 勤務時間数を削除
                $WorkingHourDeleteService->deleteWorkingHour($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('working_hour.index')->with([
            'alert_type' => 'success',
            'alert_message' => '勤務時間数を削除しました。',
        ]);
    }
}