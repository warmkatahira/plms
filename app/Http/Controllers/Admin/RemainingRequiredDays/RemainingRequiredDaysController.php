<?php

namespace App\Http\Controllers\Admin\RemainingRequiredDays;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\User;
// サービス
use App\Services\Admin\RemainingRequiredDays\RemainingRequiredDaysService;
// その他
use Illuminate\Support\Facades\DB;

class RemainingRequiredDaysController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '義務残通知']);
        $employees = User::whereRaw('
                            GREATEST(0, COALESCE(carried_over_required_days, 0) + COALESCE(granted_required_days, 0) - COALESCE(used_days, 0)) > 0
                        ')
                        ->where('is_active', true)
                        ->where('is_ignored_remaining_required_days_notice', false)
                        ->with('base')
                        ->get();
        if($employees->isEmpty()){
            return view('admin.remaining_required_days.index')->with([
                'grouped'   => collect(),
                'employees' => collect(),
            ]);
        }
        // 義務残日数ごとにグループ化し、さらに営業所ごとに集計
        $grouped = $employees
            ->groupBy(fn($e) => $e->remaining_required_days)
            ->map(fn($group) => [
                'count' => $group->count(),
                'bases' => $group->groupBy('base_id')->map(fn($baseGroup) => [
                    'base_name'  => $baseGroup->first()->base?->base_name ?? '未設定',
                    'count'      => $baseGroup->count(),
                    'sort_order' => $baseGroup->first()->base?->sort_order ?? 999,
                    // 営業所内の従業員リストを追加
                    'employees'  => $baseGroup->map(fn($e) => [
                        'name'                    => $e->user_name,
                        'employee_code'           => $e->employee_code,
                        'remaining_required_days' => $e->remaining_required_days,
                    ])->sortBy('employee_code')->values(),
                ])->values()->sortBy('sort_order')->values(),
            ])
            ->sortKeysDesc();
        return view('admin.remaining_required_days.index')->with([
            'grouped'   => $grouped,
            'employees' => $employees,
        ]);
    }

    public function enter(Request $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $RemainingRequiredDaysService = new RemainingRequiredDaysService;
                // メール送信処理
                $RemainingRequiredDaysService->sendMail();
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '義務残通知が完了しました。',
        ]);
    }
}