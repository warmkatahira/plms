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
    // 並び替えて取得
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
    // routesテーブルとのリレーション
    public function routes()
    {
        return $this->hasMany(Route::class, 'route_type_id', 'route_type_id');
    }
}
