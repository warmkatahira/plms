<?php

namespace App\Services\Admin\Employee;

// モデル
use App\Models\User;
use App\Models\PaidLeave;
use App\Models\StatutoryLeave;
// 列挙
use App\Enums\RoleEnum;
use App\Enums\SystemEnum;
// その他
use App\Mail\UserCreateNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmployeeCreateService
{
    // ユーザーを追加
    public function createEmployee($request)
    {
        // 初期ログインパスワードを取得（英数字12桁）
        $password = Str::random(12);
        // 従業員を追加
        $user = User::create([
            'status'                                        => $request->status,
            'base_id'                                       => $request->base_id,
            'employee_no'                                   => $request->employee_no,
            'user_name'                                     => $request->user_name,
            'user_id'                                       => $request->user_id,
            'is_auto_update_statutory_leave_remaining_days' => $request->is_auto_update_statutory_leave_remaining_days,
            'role_id'                                       => RoleEnum::USER,
            'password'                                      => Hash::make($password),
        ]);
        // 有給関連テーブルへレコード追加
        $this->createPaidLeave($user);
        // アカウント発行通知メールを送信
        $this->sendMail($user, $password);
    }

    // 有給関連テーブルへレコード追加
    public function createPaidLeave($user)
    {
        // 有給管理テーブルへ追加
        PaidLeave::create([
            'user_no'                   => $user->user_no,
            'paid_leave_granted_days'   => 0,
            'paid_leave_remaining_days' => 0,
            'paid_leave_used_days'      => 0,
            'daily_working_hours'       => 0,
            'half_day_working_hours'    => 0,
        ]);
        // 有給義務管理テーブルへ追加
        StatutoryLeave::create([
            'user_no'                           => $user->user_no,
            'statutory_leave_expiration_date'   => null,
            'statutory_leave_days'              => 0,
            'statutory_leave_remaining_days'    => 0,
        ]);
    }

    // アカウント発行通知メールを送信
    public function sendMail($user, $password)
    {
        // +-+-+-+-+-+-+-+-+-+-   アカウント発行通知メール   +-+-+-+-+-+-+-+-+-+-
        // インスタンス化
        $mail = new UserCreateNotificationMail($user, $password);
        // Toを設定
        $mail->to(Auth::user()->email);
        // 件名を設定
        $mail->subject('【'.SystemEnum::getSystemTitle().'】従業員追加通知');
        // メールを送信
        Mail::send($mail);
        // +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
    }
}