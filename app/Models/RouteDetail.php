<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteDetail extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'route_detail_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'route_id',
        'boarding_location_id',
        'stop_order',
        'arrival_time',
        'departure_time',
    ];
}
