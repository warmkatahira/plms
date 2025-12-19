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

class StockAllocateService
{
    // 在庫引当処理
    public function allocateStock($allocate_orders)
    {
        // 引当対象在庫のロック（引当対象がいるかの結果も返す）
        $result = $this->lockStockForAllocation($allocate_orders);
        // 引当対象がいる場合
        if($result){
            // order_item_componentsテーブル単位の在庫引当処理
            $this->allocateForOrderItemComponent($allocate_orders);
            // order_itemsテーブル単位の在庫引当処理
            $this->allocateForOrderItem($allocate_orders);
        }
    }

    // 引当対象在庫のロック（引当対象がいるかの結果も返す）
    public function lockStockForAllocation($allocate_orders)
    {
        // 在庫引当対象の商品IDと出荷倉庫IDを重複を除いて取得
        $stock_allocate_items = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                ->join('order_item_components', 'order_item_components.order_item_id', 'order_items.order_item_id')
                                ->whereIn('orders.order_control_id', $allocate_orders)
                                ->where('order_item_components.is_stock_allocated', false)
                                ->select('shipping_base_id', 'allocated_component_item_id')
                                ->groupBy('shipping_base_id', 'allocated_component_item_id')
                                ->get();
        // レコードが取得できていない場合
        if($stock_allocate_items->isEmpty()){
            // 処理を抜ける
            return false;
        }
        // 一時テーブルを作成
        DB::statement('
            CREATE TEMPORARY TABLE temp_stock_allocate_items (
                base_id VARCHAR(10) COLLATE utf8mb4_unicode_ci,
                item_id INT
            );
        ');
        // 一時テーブルに追加するデータを取得
        $values = $stock_allocate_items->map(function ($item){
                        return "('" . $item->shipping_base_id . "'," . (int) $item->allocated_component_item_id . ")";
                    })->implode(',');
        // 一時テーブルに追加
        DB::statement("
            INSERT INTO temp_stock_allocate_items (base_id, item_id)
            VALUES $values
        ");
        // 在庫テーブルと一時テーブルを結合して、引当対象の在庫をロック
        Stock::join('temp_stock_allocate_items', function ($join){
                    $join->on('stocks.base_id', '=', 'temp_stock_allocate_items.base_id')
                        ->on('stocks.item_id', '=', 'temp_stock_allocate_items.item_id');
                })
                ->select('stocks.*')
                ->lockForUpdate()
                ->get();
        return true;
    }

    // order_item_componentsテーブル単位の在庫引当処理
    public function allocateForOrderItemComponent($allocate_orders)
    {
        // 在庫引当の条件を満たしていて在庫管理していない商品のレコードを取得
        $order_item_component_ids = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                        ->join('order_item_components', 'order_item_components.order_item_id', 'order_items.order_item_id')
                                        ->whereIn('orders.order_control_id', $allocate_orders)
                                        ->where('order_item_components.is_stock_allocated', false)
                                        ->where('order_item_components.is_stock_managed_snapshot', false)
                                        ->pluck('order_item_components.order_item_component_id');
        // 在庫引当をOKに更新(在庫管理をしていない商品は無条件で在庫引当OKにしている)
        OrderItemComponent::whereIn('order_item_component_id', $order_item_component_ids)->update([
            'is_stock_allocated'    => 1,
            'unallocated_quantity'  => 0,
        ]);
        // 在庫引当の条件を満たしていて在庫管理している商品のレコードを取得(注文番号で昇順をかけている)
        $stock_allocate_orders = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                    ->join('order_item_components', 'order_item_components.order_item_id', 'order_items.order_item_id')
                                    ->whereIn('orders.order_control_id', $allocate_orders)
                                    ->where('order_item_components.is_stock_allocated', false)
                                    ->where('order_item_components.is_stock_managed_snapshot', true)
                                    ->select('order_item_component_id', 'shipping_base_id', 'allocated_component_item_id', 'unallocated_quantity')
                                    ->orderBy('order_no', 'asc')
                                    ->get();
        // 引当対象の分だけループ
        foreach($stock_allocate_orders as $stock_allocate_order){
            // 引当対象のレコードを取得
            $order_item_component = OrderItemComponent::getSpecify($stock_allocate_order->order_item_component_id)->first();
            // 出荷倉庫IDと商品IDを条件に有効在庫数が1以上の在庫を取得
            $stock = Stock::where('base_id', $stock_allocate_order->shipping_base_id)
                        ->where('item_id', $stock_allocate_order->allocated_component_item_id)
                        ->where('available_stock', '>', 0)
                        ->first();
            // 引き当てられる在庫がない場合
            if(is_null($stock)){
                // 次のループ処理へ
                continue;
            }
            // 有効在庫数が未引当数と同じか多い場合
            if($stock->available_stock >= $order_item_component->unallocated_quantity){
                // 有効在庫数から未引当数を引く
                $stock->decrement('available_stock', $order_item_component->unallocated_quantity);
                // 在庫引当OK処理(未引当数も同時に0にする)
                $order_item_component->update([
                    'is_stock_allocated'    => 1,
                    'unallocated_quantity'  => 0,
                ]);
            }
            // 有効在庫数が未引当数よりも少ない場合
            if($stock->available_stock < $order_item_component->unallocated_quantity){
                // 確保した在庫の分だけ未引当数を減らす
                $order_item_component->decrement('unallocated_quantity', $stock->available_stock);
                // 有効在庫数を0にする
                $stock->update(['available_stock' => 0]);
            }
        }
    }

    // order_itemsテーブル単位の在庫引当処理
    public function allocateForOrderItem($allocate_orders)
    {
        // 対象を取得
        $order_items = OrderItem::whereIn('order_control_id', $allocate_orders)->with('order_item_components')->get();
        // 対象の分だけループ処理
        foreach($order_items as $order_item){
            // 商品引当が0(NG)の場合
            if($order_item->is_item_allocated === 0){
                // 何も更新しないので、次のループへ
                continue;
            }
            // 商品引当が1(OK)の場合
            if($order_item->is_item_allocated === 1){
                // 配下レコードの在庫引当NG数が1以上ある場合
                if($order_item->order_item_components->where('is_stock_allocated', false)->count() >= 1){
                    // 何も更新しないので、次のループへ
                    continue;
                }
                // 在庫引当を1(OK)に更新
                $order_item->update([
                    'is_stock_allocated' => 1,
                ]);
            }
        }
    }
}