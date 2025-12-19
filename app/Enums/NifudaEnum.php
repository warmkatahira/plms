<?php

namespace App\Enums;

enum NifudaEnum
{
    const ORDER_NO              = '[注文番号]';
    const ORDER_CONTROL_ID      = '[受注管理ID]';
    const ITEM_CATEGORY_1       = '[商品カテゴリ1]';
    const ITEM_CATEGORY_2       = '[商品カテゴリ2]';
    const TOTAL_SHIP_QUANTITY   = '[合計出荷数]';

    // 荷札品名を作成
    public static function create_product_name($product_name, $order)
    {
        // nullの場合は空文字を返す
        if(is_null($product_name)){
            return '';
        }
        // [注文番号]の場合
        if($product_name === self::ORDER_NO){
            return $order->order_no;
        }
        // [受注管理ID]の場合
        if($product_name === self::ORDER_CONTROL_ID){
            return $order->order_control_id;
        }
        // [商品カテゴリ1]の場合
        if($product_name === self::ITEM_CATEGORY_1){
            return $order->order_items->first()->order_item_components->first()->item->item_category_1 ?? '';
        }
        // [商品カテゴリ2]の場合
        if($product_name === self::ITEM_CATEGORY_2){
            return $order->order_items->first()->order_item_components->first()->item->item_category_2 ?? '';
        }
        // [合計出荷数]の場合
        if($product_name === self::TOTAL_SHIP_QUANTITY){
            return '合計出荷数：' . $order->getTotalShipQuantity() . '点' ?? '';
        }
        return $product_name;
    }
}
