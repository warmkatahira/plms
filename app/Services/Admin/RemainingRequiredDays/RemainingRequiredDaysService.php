<?php

namespace App\Services\Admin\RemainingRequiredDays;

// モデル
use App\Models\User;
// 列挙
use App\Enums\RoleEnum;
use App\Enums\ExcludedNotificationEmailEnum;
// メール
use Illuminate\Support\Facades\Mail;
use App\Mail\RemainingRequiredDaysMail;

class RemainingRequiredDaysService
{
    // メール送信処理
    public function sendMail()
    {
        // 開発環境の場合は行わない
        if(config('app.env') === 'local'){
            return;
        }
        // 義務残がある従業員を取得
        $employees = User::whereRaw('
                            GREATEST(0, COALESCE(carried_over_required_days, 0) + COALESCE(granted_required_days, 0) - COALESCE(used_days, 0)) > 0
                        ')
                        ->where('is_active', true)
                        ->where('is_ignored_remaining_required_days_notice', false)
                        ->with('base')
                        ->orderBy('employee_no', 'asc')
                        ->get();
        if ($employees->isEmpty()) return;
        // 通知除外メールアドレスを取得
        $excluded = ExcludedNotificationEmailEnum::values();
        // admin・system_adminを全員取得（営業所問わず）
        $admin_emails = User::whereIn('role_id', [RoleEnum::ADMIN, RoleEnum::SYSTEM_ADMIN])
                                ->where('is_active', true)
                                ->whereNotNull('email')
                                ->pluck('email')
                                ->reject(fn($email) => in_array($email, $excluded))
                                ->values()
                                ->toArray();
        // 営業所ごとにグループ化
        $grouped_by_base = $employees->groupBy('base_id');
        foreach ($grouped_by_base as $base_id => $base_employees) {
            // その営業所の所長権限ユーザーを取得
            $base_admins = User::where('base_id', $base_id)
                                ->where('role_id', RoleEnum::BASE_ADMIN)
                                ->where('is_active', true)
                                ->whereNotNull('email')
                                ->get();
            // 営業所名を取得
            $base_name = $base_employees->first()->base?->base_name ?? '未設定';
            // 従業員情報一覧（名前と義務残日数）
            $employee_list = $base_employees->map(fn($e) => [
                'user_name'                 => $e->user_name,
                'remaining_required_days'   => $e->remaining_required_days,
            ])->values();
            // 送信先（所長（いれば） + メール登録済みの本人）
            $to_emails = collect($base_admins->pluck('email'))
                            ->merge($base_employees->pluck('email')->filter())
                            ->unique()
                            ->reject(fn($email) => in_array($email, $excluded))
                            ->values()
                            ->toArray();
            // 宛先（To）が無い営業所は、管理者をToに切り替えて通知を担保する
            if (empty($to_emails)) {
                // 管理者をToにして送信（bcc依存をやめ確実に届ける）
                Mail::to($admin_emails)
                        ->send(new RemainingRequiredDaysMail($base_name, $employee_list));
                // 次の営業所へ
                continue;
            }
            // 通常送信（所長・本人をTo、管理者をBcc）
            Mail::to($to_emails)
                    ->bcc($admin_emails)
                    ->send(new RemainingRequiredDaysMail($base_name, $employee_list));
        }
    }
}