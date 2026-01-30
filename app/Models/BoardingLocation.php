<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardingLocation extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'boarding_location_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'location_name',
        'location_memo',
        'is_active',
        'sort_order',
    ];
    // 主キーで検索するスコープ
    public function scopeByPk($query, $id)
    {
        return $query->whereKey($id);
    }
}
