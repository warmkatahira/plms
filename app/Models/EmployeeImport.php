<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeImport extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'import_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'status',
        'short_base_name',
        'employee_no',
        'user_name',
        'user_id',
        'password',
        'paid_leave_granted_days',
        'paid_leave_remaining_days',
        'paid_leave_used_days',
        'daily_working_hours',
        'half_day_working_hours',
        'is_auto_update_statutory_leave_remaining_days',
        'statutory_leave_expiration_date',
        'statutory_leave_days',
        'statutory_leave_remaining_days',
    ];
}
