<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaidLeave extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'paid_leave_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'user_no',
        'paid_leave_granted_days',
        'paid_leave_remaining_days',
        'paid_leave_used_days',
        'daily_working_hours',
        'half_day_working_hours',
    ];
}