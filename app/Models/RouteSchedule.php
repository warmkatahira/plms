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
    // 運行状況の文字列を返すアクセサ
    public function getIsActiveTextAttribute()
    {
        return $this->is_active ? '運行決定' : '運行未定';
    }
    // route_typesテーブルとのリレーション
    public function route_type()
    {
        return $this->belongsTo(RouteType::class, 'route_type_id', 'route_type_id');
    }
    // vehiclesテーブルとのリレーション
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'use_vehicle_id', 'vehicle_id');
    }
    // usersテーブルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'driver_user_no', 'user_no');
    }
}
