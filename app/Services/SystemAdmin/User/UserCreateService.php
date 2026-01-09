<?php

namespace App\Services\SystemAdmin\User;

// モデル
use App\Models\User;
// その他
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
        User::create([
            'status'                                        => $request->status,
            'base_id'                                       => $request->base_id,
            'employee_no'                                   => $request->employee_no,
            'user_name'                                     => $request->user_name,
            'user_id'                                       => $request->user_id,
            'is_auto_update_statutory_leave_remaining_days' => $request->is_auto_update_statutory_leave_remaining_days,
            'role_id'                                       => $request->role_id,
            'password'                                      => Hash::make($password),
        ]);
    }
}