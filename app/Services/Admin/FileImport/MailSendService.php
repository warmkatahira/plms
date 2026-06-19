<?php

namespace App\Services\Admin\FileImport;

// モデル
use App\Models\User;
// 列挙
use App\Enums\GrantTypeEnum;
use App\Enums\RoleEnum;
use App\Enums\ExcludedNotificationEmailEnum;
// メール
use Illuminate\Support\Facades\Mail;
use App\Mail\FirstGrantMail;

class MailSendService
{
    // 初回付与通知を実施
    public function processFirstGrant($grant_employees)
    {
        // 開発環境の場合は行わない
        if(config('app.env') === 'local'){
            return;
        }
        // 初回付与の従業員を取得
        $first_grant_employees = User::whereIn('employee_no', $grant_employees->pluck('employee_no'))
                                        ->where('grant_type', GrantTypeEnum::FIRST)
                                        ->with('base')
                                        ->orderBy('employee_no', 'asc')
                                        ->get();
        // 初回付与の従業員がいない場合は、処理を抜ける
        if ($first_grant_employees->isEmpty()) return;
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
        $grouped_by_base = $first_grant_employees->groupBy('base_id');
        // 営業所の分だけループ処理
        foreach($grouped_by_base as $base_id => $base_employees){
            // その営業所の所長権限ユーザーを取得
            $base_admins = User::where('base_id', $base_id)
                                ->where('role_id', RoleEnum::BASE_ADMIN)
                                ->where('is_active', true)
                                ->whereNotNull('email')
                                ->get();
            // 従業員名一覧を作成
            $employee_names = $base_employees->pluck('user_name')->toArray();
            // 営業所名を取得
            $base_name = $base_employees->first()->base?->base_name ?? '未設定';
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
                        ->send(new FirstGrantMail($base_name, $employee_names));
                // 次の営業所へ
                continue;
            }
            // 通常送信（所長・本人をTo、管理者をBcc）
            Mail::to($to_emails)
                    ->bcc($admin_emails)
                    ->send(new FirstGrantMail($base_name, $employee_names));
        }
    }
}