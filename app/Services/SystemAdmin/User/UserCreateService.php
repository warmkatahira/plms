<?php

namespace App\Services\SystemAdmin\User;

// モデル
use App\Models\User;
// 列挙
use App\Enums\SystemEnum;
// その他
use App\Mail\UserCreateNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserCreateService
{
    // ユーザーを追加
    public function createUser($request)
    {
        // 初期ログインパスワードを取得（英数字12桁）
        $password = Str::random(12);
        // ユーザーを追加
        $user = User::create([
            'user_id'       => $request->user_id,
            'last_name'     => $request->last_name,
            'first_name'    => $request->first_name,
            'password'      => Hash::make($password),
            'email'         => $request->email,
            'status'        => $request->status,
            'role_id'       => $request->role_id,
            'company_id'    => $request->company_id,
        ]);
        // アカウント発行通知メールを送信
        $this->sendMail($user, $password);
    }

    // アカウント発行通知メールを送信
    public function sendMail($user, $password)
    {
        // +-+-+-+-+-+-+-+-+-+-   アカウント発行通知メール   +-+-+-+-+-+-+-+-+-+-
        // インスタンス化
        $mail = new UserCreateNotificationMail($user, $password);
        // Toを設定
        $mail->to($user->email);
        // 件名を設定
        $mail->subject('【'.SystemEnum::getSystemTitle().'】アカウント発行完了通知');
        // メールを送信
        Mail::send($mail);
        // +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
    }
}