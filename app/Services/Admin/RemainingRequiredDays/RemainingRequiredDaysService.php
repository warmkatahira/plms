<?php

namespace App\Services\Admin\RemainingRequiredDays;

// モデル
use App\Models\User;
// 列挙
use App\Enums\RoleEnum;
// メール
use Illuminate\Support\Facades\Mail;
use App\Mail\RemainingRequiredDaysMail;

class RemainingRequiredDaysService
{
    // メール送信処理
    public function sendMail()
    {
        // 義務残がある従業員を取得
        $employees = User::whereRaw('
                            GREATEST(0, COALESCE(carried_over_required_days, 0) + COALESCE(granted_required_days, 0) - COALESCE(used_days, 0)) > 0
                        ')
                        ->where('is_active', true)
                        ->with('base')
                        ->get();
        if ($employees->isEmpty()) return;
        // 営業所ごとにグループ化
        $grouped_by_base = $employees->groupBy('base_id');
        foreach ($grouped_by_base as $base_id => $base_employees) {
            // 所長を取得
            $base_admins = User::where('base_id', $base_id)
                                ->where('role_id', RoleEnum::BASE_ADMIN)
                                ->where('is_active', true)
                                ->get();

            // 送信先（所長 + メール登録済みの本人）
            $to_emails = $base_admins->pluck('email')
                ->merge($base_employees->pluck('email')->filter())
                ->unique()
                ->toArray();
            if (empty($to_emails)) continue;
            // 従業員情報一覧（名前と義務残日数）
            $employee_list = $base_employees->map(fn($e) => [
                'user_name'              => $e->user_name,
                'remaining_required_days' => $e->remaining_required_days,
            ])->sortByDesc('remaining_required_days')->values();
            Mail::to($to_emails)->send(new RemainingRequiredDaysMail($base_admins, $employee_list));
        }
    }
}