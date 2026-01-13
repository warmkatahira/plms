<?php

namespace App\Services\Admin\Employee;

// モデル
use App\Models\User;

class EmployeeUpdateService
{
    // 従業員を更新
    public function updateEmployee($request)
    {
        // 従業員を更新
        User::getSpecify($request->user_no)->update([
            'status'                                        => $request->status,
            'base_id'                                       => $request->base_id,
            'employee_no'                                   => $request->employee_no,
            'user_name'                                     => $request->user_name,
            'is_auto_update_statutory_leave_remaining_days' => $request->is_auto_update_statutory_leave_remaining_days,
        ]);
    }
}