<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'vehicle_type_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'vehicle_type',
        'sort_order',
    ];
}
