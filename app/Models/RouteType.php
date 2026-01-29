<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteType extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'route_type_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'route_type',
        'sort_order',
    ];
}
