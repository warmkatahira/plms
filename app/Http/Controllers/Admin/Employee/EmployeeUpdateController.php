<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Base;
use App\Models\User;
// サービス
use App\Services\Admin\Employee\EmployeeUpdateService;
// リクエスト
use App\Http\Requests\Admin\Employee\EmployeeUpdateRequest;
// 列挙
use App\Enums\WorkingHoursEnum;
// その他
use Illuminate\Support\Facades\DB;

class EmployeeUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '従業員更新']);
        // 従業員を取得
        $employee = User::getSpecify($request->user_no)->firstOrFail();
        // 営業所を取得
        $bases = Base::getAll()->get();
        // 1日あたりの時間数を取得
        $daily_working_hours = WorkingHoursEnum::DAILY_WORKING_HOURS;
        // 半日あたりの時間数を取得
        $half_day_working_hours = WorkingHoursEnum::HALF_DAY_WORKING_HOURS;
        return view('admin.employee.update')->with([
            'employee' => $employee,
            'bases' => $bases,
            'daily_working_hours' => $daily_working_hours,
            'half_day_working_hours' => $half_day_working_hours,
        ]);
    }

    public function update(EmployeeUpdateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $EmployeeUpdateService = new EmployeeUpdateService;
                // 従業員を更新
                $EmployeeUpdateService->updateEmployee($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('employee.index')->with([
            'alert_type' => 'success',
            'alert_message' => '従業員を更新しました。',
        ]);
    }
}