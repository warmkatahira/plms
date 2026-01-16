<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatutoryLeaveImport extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'statutory_leave_import_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'employee_no',
        'user_name',
        'statutory_leave_expiration_date',
        'statutory_leave_days',
        'statutory_leave_remaining_days',
    ];
}
