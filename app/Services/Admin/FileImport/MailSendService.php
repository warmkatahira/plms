<?php

namespace App\Services\Admin\FileImport;

// モデル
use App\Models\User;
// 列挙
use App\Enums\GrantTypeEnum;
use App\Enums\RoleEnum;
// メール
use Illuminate\Support\Facades\Mail;
use App\Mail\FirstGrantMail;

class MailSendService
{
    // 初回付与通知を実施
    public function processFirstGrant($grant_employees)
    {
        // 初回付与の従業員を取得
        $first_grant_employees = User::whereIn('employee_no', $grant_employees->pluck('employee_no'))
                                        ->where('grant_type', GrantTypeEnum::FIRST)
                                        ->with('base')
                                        ->get();
        // 初回付与の従業員がいない場合は、処理を抜ける
        if ($first_grant_employees->isEmpty()) return;
        // admin・system_adminを全員取得（営業所問わず）
        $admin_emails = User::whereIn('role_id', [RoleEnum::ADMIN, RoleEnum::SYSTEM_ADMIN])
                                ->where('is_active', true)
                                ->whereNotNull('email')
                                ->pluck('email')
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
            /* // 送信先（所長（いれば） + メール登録済みの本人）
            $to_emails = collect($base_admins->pluck('email'))
                            ->merge($base_employees->pluck('email')->filter())
                            ->unique()
                            ->toArray(); */
            // テスト用：所長なし・固定アドレスに送信
            $to_emails = ['t.katahira@warm.co.jp'];
            // メール送信
            Mail::to($to_emails)
                    ->bcc($admin_emails)
                    ->send(new FirstGrantMail($base_name, $employee_names));
        }
    }
}