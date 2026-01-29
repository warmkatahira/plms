<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteScheduleUser extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'route_schedule_user_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'route_schedule_detail_id',
        'user_no',
    ];
}
