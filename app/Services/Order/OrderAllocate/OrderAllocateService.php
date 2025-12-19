<?php

namespace App\Services\Order\OrderAllocate;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemComponent;
use App\Models\Stock;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;

class OrderAllocateService
{
    // 引当済み処理
    public function allocateOrder($allocate_orders)
    {
        // 対象を取得
        $orders = Order::whereIn('order_control_id', $allocate_orders)->with('order_items')->get();
        // 対象の分だけループ処理
        foreach($orders as $order){
            // 配下レコードの商品引当NG数を取得
            $item_allocated_ng_count = $order->order_items->where('is_item_allocated', false)->count();
            // 配下レコードの在庫引当NG数を取得
            $stock_allocated_ng_count = $order->order_items->where('is_stock_allocated', false)->count();
            // 優先順位: 商品引当NG > 在庫引当NG > 引当OK
            if($item_allocated_ng_count >= 1){
                $is_allocated = 0;
                $order_status_id = OrderStatusEnum::KAKUNIN_MACHI;
            }elseif($stock_allocated_ng_count >= 1){
                $is_allocated = 0;
                $order_status_id = OrderStatusEnum::HIKIATE_MACHI;
            }else{
                $is_allocated = 1;
                $order_status_id = OrderStatusEnum::SHUKKA_MACHI;
            }
            // 変数の値に沿って更新
            $order->update([
                'is_allocated'      => $is_allocated,
                'order_status_id'   => $order_status_id,
            ]);
        }
    }
}