<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteSchedule extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'route_schedule_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'route_type_id',
        'schedule_date',
        'driver_user_no',
        'use_vehicle_id',
        'route_schedule_memo',
        'is_active',
    ];
}
