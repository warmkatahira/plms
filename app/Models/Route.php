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
    // 主キーで検索するスコープ
    public function scopeByPk($query, $id)
    {
        return $query->whereKey($id);
    }
    // route_detailsテーブルとのリレーション
    public function route_details()
    {
        return $this->hasMany(RouteDetail::class, 'route_id', 'route_id')
                    ->orderBy('stop_order', 'asc');
    }
    // route_typesテーブルとのリレーション
    public function route_type()
    {
        return $this->belongsTo(RouteType::class, 'route_type_id', 'route_type_id');
    }
    // vehicle_categoriesテーブルとのリレーション
    public function vehicle_category()
    {
        return $this->belongsTo(VehicleCategory::class, 'vehicle_category_id', 'vehicle_category_id');
    }
    // 有効/無効の文字列を返すアクセサ
    public function getIsActiveTextAttribute()
    {
        return $this->is_active ? '有効' : '無効';
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            '有効/無効',
            'ルート区分',
            '車両種別',
            'ルート名',
            '乗降場所数',
            '並び順',
            '最終更新日時',
            '場所名',
            '場所メモ',
            '停車順番',
            '着　→　発',
            '次の地点まで',
        ];
    }
}
