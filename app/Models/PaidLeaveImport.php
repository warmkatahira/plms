<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaidLeaveImport extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'paid_leave_import_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'employee_no',
        'paid_leave_granted_days',
        'paid_leave_used_days',
        'paid_leave_remaining_days',
    ];
}
