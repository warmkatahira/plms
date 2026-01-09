<?php

namespace App\Services\Admin\Employee;

// モデル
use App\Models\User;
use App\Models\PaidLeave;
use App\Models\StatutoryLeave;
// 列挙
use App\Enums\RoleEnum;
// その他
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class EmployeeCreateService
{
    // ユーザーを追加
    public function createEmployee($request)
    {
        // 初期ログインパスワードを取得（英数字12桁）
        $password = Str::random(12);
        // 従業員を追加
        return User::create([
            'status'                                        => $request->status,
            'base_id'                                       => $request->base_id,
            'employee_no'                                   => $request->employee_no,
            'user_name'                                     => $request->user_name,
            'user_id'                                       => $request->user_id,
            'is_auto_update_statutory_leave_remaining_days' => $request->is_auto_update_statutory_leave_remaining_days,
            'role_id'                                       => RoleEnum::USER,
            'password'                                      => Hash::make($password),
        ]);
    }

    // 有給関連テーブルへレコード追加
    public function createPaidLeave($employee)
    {
        // 有給管理テーブルへ追加
        PaidLeave::create([
            'user_no'                   => $employee->user_no,
            'paid_leave_granted_days'   => 0,
            'paid_leave_remaining_days' => 0,
            'paid_leave_used_days'      => 0,
            'daily_working_hours'       => 0,
            'half_day_working_hours'    => 0,
        ]);
        // 有給義務管理テーブルへ追加
        StatutoryLeave::create([
            'user_no'                           => $employee->user_no,
            'statutory_leave_expiration_date'   => null,
            'statutory_leave_days'              => 0,
            'statutory_leave_remaining_days'    => 0,
        ]);
    }
}