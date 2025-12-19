<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemComponent extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_item_component_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'order_item_id',
        'is_stock_allocated',
        'unallocated_quantity',
        'allocated_component_item_id',
        'ship_quantity',
        'item_code_snapshot',
        'item_jan_code_snapshot',
        'item_name_snapshot',
        'is_stock_managed_snapshot',
        'is_shipping_inspection_required_snapshot',
        'is_hide_on_delivery_note_snapshot',
    ];
    // 指定したレコードを取得
    public static function getSpecify($order_item_component_id)
    {
        return self::where('order_item_component_id', $order_item_component_id);
    }
    // 指定したレコードを取得
    public static function getSpecifyByOrderItemId($order_item_id)
    {
        return self::where('order_item_id', $order_item_id);
    }
    // itemsテーブルとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class, 'allocated_component_item_id', 'item_id');
    }
    // order_itemsテーブルとのリレーション
    public function order_item()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'order_item_id');
    }
}
