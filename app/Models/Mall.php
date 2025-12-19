<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mall extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'mall_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'mall_name',
        'mall_image_file_name',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('mall_id', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($mall_id)
    {
        return self::where('mall_id', $mall_id);
    }
    // item_mallテーブルとのリレーション(中間テーブル)
    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_mall');
    }
    // order_categoriesテーブルとのリレーション
    public function order_categories()
    {
        return $this->hasMany(OrderCategory::class, 'mall_id', 'mall_id');
    }
}
