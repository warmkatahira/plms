<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'vehicle_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'user_no',
        'vehicle_type_id',
        'vehicle_category_id',
        'vehicle_name',
        'vehicle_color',
        'vehicle_number',
        'vehicle_capacity',
        'vehicle_memo',
    ];
}
