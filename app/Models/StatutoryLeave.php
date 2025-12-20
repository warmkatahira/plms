<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatutoryLeave extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'statutory_leave_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'user_no',
        'statutory_leave_expiration_date',
        'statutory_leave_days',
        'statutory_leave_remaining_days',
    ];
}