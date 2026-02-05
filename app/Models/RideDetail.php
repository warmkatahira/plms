<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RideDetail extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'ride_detail_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'ride_id',
        'boarding_location_id',
        'location_name',
        'location_memo',
        'stop_order',
        'arrival_time',
        'departure_time',
    ];
}
