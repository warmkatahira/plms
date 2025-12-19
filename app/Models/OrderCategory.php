<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCategory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_category_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'order_category_name',
        'mall_id',
        'shipper_id',
        'nifuda_product_name_1',
        'nifuda_product_name_2',
        'nifuda_product_name_3',
        'nifuda_product_name_4',
        'nifuda_product_name_5',
        'app_id',
        'access_token',
        'api_key',
        'sort_order',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('sort_order', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($order_category_id)
    {
        return self::where('order_category_id', $order_category_id);
    }
    // mallsテーブルとのリレーション
    public function mall()
    {
        return $this->belongsTo(Mall::class, 'mall_id', 'mall_id');
    }
    // shippersテーブルとのリレーション
    public function shipper()
    {
        return $this->belongsTo(Shipper::class, 'shipper_id', 'shipper_id');
    }
    // API設定があるかを返すアクセサ
    public function getApiSettingTextAttribute()
    {
        return $this->access_token ? '有' : '無';
    }
}
