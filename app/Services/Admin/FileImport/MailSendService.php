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
                                    ->get();
        // 初回付与の従業員がいない場合は、処理を抜ける
        if ($first_grant_employees->isEmpty()) return;
        // 営業所ごとにグループ化
        $grouped_by_base = $first_grant_employees->groupBy('base_id');
        // 営業所の分だけループ処理
        foreach($grouped_by_base as $base_id => $employees){
            // その営業所の所長権限ユーザーを取得
            $base_admins = User::where('base_id', $base_id)
                                ->where('role_id', RoleEnum::BASE_ADMIN)
                                ->where('is_active', true)
                                ->get();
            // 所長権限ユーザーがいない場合
            if ($base_admins->isEmpty()) continue;
            // 従業員名一覧を作成
            $employee_names = $employees->pluck('user_name')->toArray();
            // 送信先メールアドレスを作成（base_admin + メール登録済みの従業員）
            $to_emails = $base_admins->pluck('email')
                            ->merge($employees->pluck('email')->filter())
                            ->unique()
                            ->toArray();
            // base_admin全員にメール送信
            Mail::to($to_emails)->send(new FirstGrantMail($base_admins, $employee_names));
        }
    }
}