<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// トレイト
use App\Traits\ColumnEnChangeTrait;

class Item extends Model
{
    use ColumnEnChangeTrait;

    // 主キーカラムを変更
    protected $primaryKey = 'item_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'item_code',
        'item_jan_code',
        'item_name',
        'item_category_1',
        'item_category_2',
        'is_stock_managed',
        'is_shipping_inspection_required',
        'is_hide_on_delivery_note',
        'item_image_file_name',
        'sort_order',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('sort_order', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($item_id)
    {
        return self::where('item_id', $item_id);
    }
    // 指定したレコードを取得
    public static function getSpecifyByItemCode($item_code)
    {
        return self::where('item_code', $item_code);
    }
    // 指定したレコードを取得
    public static function getSpecifyByItemJanCode($item_jan_code)
    {
        return self::where('item_jan_code', $item_jan_code);
    }
    // order_itemsとのリレーション
    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'allocated_item_id', 'item_id');
    }
    // stocksテーブルとのリレーション
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'item_id', 'item_id');
    }
    // shipping_methodsテーブルとのリレーション
    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id', 'shipping_method_id');
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            '商品ID',
            '商品コード',
            '商品JANコード',
            '商品名',
            '商品カテゴリ1',
            '商品カテゴリ2',
            '在庫管理',
            '出荷検品要否',
            '納品書表示',
            '並び順',
            '商品画像',
            '最終更新日時',
        ];
    }
    // 英語カラム変換用
    const EN_CHANGE_LIST = [
        '商品コード'            => 'item_code',
        '商品JANコード'         => 'item_jan_code',
        '商品名'                => 'item_name',
        '商品カテゴリ1'         => 'item_category_1',
        '商品カテゴリ2'         => 'item_category_2',
        '在庫管理'              => 'is_stock_managed',
        '出荷検品要否'          => 'is_shipping_inspection_required',
        '納品書表示'            => 'is_hide_on_delivery_note',
        '並び順'                => 'sort_order',
    ];
    // 運送会社と配送方法を返すアクセサ
    public function getDeliveryCompanyAndShippingMethodAttribute(): string
    {
        return $this->shipping_method->delivery_company->delivery_company . ' ' . $this->shipping_method->shipping_method;
    }
    // 「is_stock_managed」に基づいて、有効 or 無効を返すアクセサ
    public function getIsStockManagedTextAttribute(): string
    {
        return $this->is_stock_managed ? '有効' : '無効';
    }
    // 「is_shipping_inspection_required」に基づいて、有効 or 無効を返すアクセサ
    public function getIsShippingInspectionRequiredTextAttribute(): string
    {
        return $this->is_shipping_inspection_required ? '要' : '否';
    }
    // 「is_hide_on_delivery_note」に基づいて、有効 or 無効を返すアクセサ
    public function getIsHideOnDeliveryNoteTextAttribute(): string
    {
        return $this->is_hide_on_delivery_note ? '非表示' : '表示';
    }
    // 商品コードから商品IDを取得
    public static function getItemIdByItemCode($item_code)
    {
        // 商品コードから商品IDを取得
        $item_id = self::where('item_code', $item_code)->value('item_id');
        // 存在していない場合は、渡された値を返す
        return $item_id ?? $item_code;
    }
}
