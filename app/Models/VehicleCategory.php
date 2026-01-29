<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCategory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'vehicle_category_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'vehicle_category',
        'sort_order',
    ];
}
