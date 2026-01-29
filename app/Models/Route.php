<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'route_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'route_name',
        'vehicle_category_id',
        'route_type_id',
        'is_active',
        'sort_order',
    ];
}
