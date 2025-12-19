<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderImportPattern extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_import_pattern_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'pattern_name',
        'pattern_description',
        'column_get_method',
        'order_category_id',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('order_import_pattern_id', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($order_import_pattern_id)
    {
        return self::where('order_import_pattern_id', $order_import_pattern_id);
    }
    // order_import_pattern_detailsテーブルとのリレーション
    public function order_import_pattern_details()
    {
        return $this->hasMany(OrderImportPatternDetail::class, 'order_import_pattern_id', 'order_import_pattern_id');
    }
    // order_categoriesテーブルとのリレーション
    public function order_category()
    {
        return $this->belongsTo(OrderCategory::class, 'order_category_id', 'order_category_id');
    }
}
