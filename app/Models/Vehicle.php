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
        'is_active',
    ];
    // 主キーで検索するスコープ
    public function scopeByPk($query, $id)
    {
        return $query->whereKey($id);
    }
    // usersテーブルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_no', 'user_no');
    }
    // vehicle_typesテーブルとのリレーション
    public function vehicle_type()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id', 'vehicle_type_id');
    }
    // vehicle_categoriesテーブルとのリレーション
    public function vehicle_category()
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id', 'vehicle_category_id');
    }
    // 所有者を返すアクセサ
    public function getOwnerAttribute()
    {
        return $this->user_no ? $this->user->full_name : '会社';
    }
    // 利用可否の文字列を返すアクセサ
    public function getIsActiveTextAttribute()
    {
        return $this->is_active ? '利用可' : '利用不可';
    }
}