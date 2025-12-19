<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_item_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'order_control_id',
        'is_item_allocated',
        'is_stock_allocated',
        'order_item_system_code',
        'order_item_code',
        'order_item_name',
        'order_quantity',
        'order_item_price',
        'allocated_item_id',
        'allocated_set_item_id',
        'item_name_snapshot',
        'is_auto_process_add',
    ];
    // 指定したレコードを取得
    public static function getSpecify($order_item_id)
    {
        return self::where('order_item_id', $order_item_id);
    }
    // 指定したレコードを取得
    public static function getSpecifyByOrderControlId($order_control_id)
    {
        return self::where('order_control_id', $order_control_id);
    }
    // ordersテーブルとのリレーション
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_control_id', 'order_control_id');
    }
    // order_item_componentsテーブルとのリレーション
    public function order_item_components()
    {
        return $this->hasMany(OrderItemComponent::class, 'order_item_id', 'order_item_id')
                    ->orderBy('order_item_components.allocated_component_item_id', 'asc');
    }
    // set_item_detailsテーブルとのリレーション
    public function set_item_details()
    {
        return $this->hasMany(SetItemDetail::class, 'set_item_id', 'allocated_set_item_id');
    }
    // itemsテーブルとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class, 'allocated_item_id', 'item_id');
    }
    // set_itemsテーブルとのリレーション
    public function set_item()
    {
        return $this->belongsTo(SetItem::class, 'allocated_set_item_id', 'set_item_id');
    }
}
