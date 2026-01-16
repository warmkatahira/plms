<?php

namespace App\Services\Admin\Employee;

// モデル
use App\Models\User;
use App\Models\PaidLeave;
use App\Models\StatutoryLeave;

class EmployeeUpdateService
{
    // 従業員を更新
    public function updateEmployee($request)
    {
        // 従業員を更新
        User::find($request->user_no)->update([
            'status'                                        => $request->status,
            'base_id'                                       => $request->base_id,
            'employee_no'                                   => $request->employee_no,
            'user_name'                                     => $request->user_name,
            'is_auto_update_statutory_leave_remaining_days' => $request->is_auto_update_statutory_leave_remaining_days,
        ]);
        // 有給管理テーブルを更新
        PaidLeave::where('user_no', $request->user_no)->update([
            'paid_leave_granted_days'   => $request->paid_leave_granted_days,
            'paid_leave_remaining_days' => $request->paid_leave_remaining_days,
            'paid_leave_used_days'      => $request->paid_leave_used_days,
            'daily_working_hours'       => $request->daily_working_hours,
            'half_day_working_hours'    => $request->half_day_working_hours,
        ]);
        // 有給義務管理テーブルを更新
        StatutoryLeave::where('user_no', $request->user_no)->update([
            'statutory_leave_expiration_date'   => $request->statutory_leave_expiration_date,
            'statutory_leave_days'              => $request->statutory_leave_days,
            'statutory_leave_remaining_days'    => $request->statutory_leave_remaining_days,
        ]);
    }
}