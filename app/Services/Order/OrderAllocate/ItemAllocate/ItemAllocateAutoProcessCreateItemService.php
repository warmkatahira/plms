<?php

namespace App\Services\Order\OrderAllocate\ItemAllocate;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemComponent;

class ItemAllocateAutoProcessCreateItemService
{
    // 自動処理で追加された商品の商品引当
    public function process($allocate_orders)
    {
        // 引当対象の中から自動処理で追加された商品を取得
        $items = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                        ->whereIn('orders.order_control_id', $allocate_orders)
                        ->where('is_item_allocated', false)
                        ->where('is_auto_process_add', true)
                        ->select('order_items.order_item_id', 'order_items.order_quantity', 'order_items.allocated_item_id')
                        ->get();
        // 商品引当OKの商品がある場合
        if($items->isNotEmpty()){
            // 商品引当OKを更新
            $this->updateItemAllocated($items);
            // 受注商品構成を追加
            $this->createOrderItemComponent($items);
        }
    }

    // 商品引当OKを更新
    public function updateItemAllocated($items)
    {
        // 更新対象のorder_item_idを配列に格納
        $order_item_ids = $items->pluck('order_item_id')->toArray();
        // 商品引当を1(OK)に更新
        OrderItem::whereIn('order_item_id', $order_item_ids)->update([
            'is_item_allocated' => 1,
        ]);
    }

    // 受注商品構成を追加
    public function createOrderItemComponent($items)
    {
        // 追加する情報を格納する配列を初期化
        $create_data = [];
        // 商品の分だけループ処理
        foreach($items as $item){
            // 追加する情報を配列に格納
            $create_data[] = [
                'order_item_id'                 => $item->order_item_id,
                'is_stock_allocated'            => 1,
                'unallocated_quantity'          => 0,
                'allocated_component_item_id'   => $item->allocated_item_id,
                'ship_quantity'                 => $item->order_quantity,
            ];
        }
        // 追加
        OrderItemComponent::upsert($create_data, 'order_item_component_id');
    }
}