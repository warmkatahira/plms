<?php

namespace App\Services\Order\OrderAllocate\ItemAllocate;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemComponent;
use App\Models\SetItemDetail;
// 列挙
use App\Enums\MallEnum;
// その他
use Illuminate\Support\Facades\DB;

class ItemAllocateMakeshopService
{
    // メイクショップからの注文の商品引当
    public function process($allocate_orders)
    {
        // 引当対象の中からメイクショップからの注文を取得(共通のベースクエリ)
        $base_query = Order::join('order_categories', 'order_categories.order_category_id', 'orders.order_category_id')
                        ->join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                        ->whereIn('orders.order_control_id', $allocate_orders)
                        ->where('order_categories.mall_id', MallEnum::MAKESHOP)
                        ->where('is_item_allocated', false);
        // item_mallにorder_item_system_codeとorder_item_codeの組み合わせが存在するレコードを取得(バリエーション商品用)
        $variation_items = (clone $base_query)
                            ->leftJoin('item_mall', function ($join) {
                                $join->on('order_items.order_item_system_code', '=', 'item_mall.mall_item_code')
                                    ->on('order_items.order_item_code', '=', 'item_mall.mall_variation_code')
                                    ->where('item_mall.mall_id', MallEnum::MAKESHOP);
                            })
                            ->whereNotNull('item_mall.item_id')
                            ->select('order_items.order_item_id', 'order_items.order_quantity', 'item_mall.item_id as matched_item_id')
                            ->get();
        // item_mallにorder_item_system_codeが存在して、mall_variation_codeがnullのレコードを取得(非バリエーション商品用)
        $non_variation_items = (clone $base_query)
                            ->leftJoin('item_mall', function ($join) {
                                $join->on('order_items.order_item_system_code', '=', 'item_mall.mall_item_code')
                                    ->whereNull('item_mall.mall_variation_code')
                                    ->where('item_mall.mall_id', MallEnum::MAKESHOP);
                            })
                            ->whereNotNull('item_mall.item_id')
                            ->select('order_items.order_item_id', 'order_items.order_quantity', 'item_mall.item_id as matched_item_id')
                            ->get();
        // set_item_mallにorder_item_system_codeが存在しているレコードを取得(セット商品用)
        $set_items = (clone $base_query)
                            ->leftJoin('set_item_mall', function ($join) {
                                $join->on('order_items.order_item_system_code', '=', 'set_item_mall.mall_set_item_code')
                                    ->where('set_item_mall.mall_id', MallEnum::MAKESHOP);
                            })
                            ->whereNotNull('set_item_mall.set_item_id')
                            ->select('order_items.order_item_id', 'order_items.order_quantity', 'set_item_mall.set_item_id as matched_set_item_id')
                            ->get();
        // バリエーション商品で商品引当OKの商品がある場合
        if($variation_items->isNotEmpty()){
            // 商品引当OKを更新
            $this->updateItemAllocated($variation_items, 'item', 'matched_item_id');
            // 受注商品構成を追加
            $this->createOrderItemComponent($variation_items, 'item');
        }
        // 非バリエーション商品で商品引当OKの商品がある場合
        if($non_variation_items->isNotEmpty()){
            // 商品引当OKを更新
            $this->updateItemAllocated($non_variation_items, 'item', 'matched_item_id');
            // 受注商品構成を追加
            $this->createOrderItemComponent($non_variation_items, 'item');
        }
        // セット商品で商品引当OKの商品がある場合
        if($set_items->isNotEmpty()){
            // 商品引当OKを更新
            $this->updateItemAllocated($set_items, 'set_item', 'matched_set_item_id');
            // 受注商品構成を追加
            $this->createOrderItemComponent($set_items, 'set_item');
        }
    }

    // 商品引当OKを更新
    public function updateItemAllocated($items, $item_type, $matched_column)
    {
        // 配列を初期化
        $cases = [];
        $order_item_ids = [];
        // 商品の分だけループ処理
        foreach($items as $item){
            // 受注商品IDと引き当てされた商品IDを変数に格納
            $order_item_id = (int) $item->order_item_id;
            $matched_id = (int) $item->$matched_column;
            // SQLの条件部分を配列に格納
            $cases[] = "WHEN {$order_item_id} THEN {$matched_id}";
            // 受注商品IDを配列に格納(WHERE IN 用)
            $order_item_ids[] = $order_item_id;
        }
        // 配列の中身を1つの変数にまとめる
        $case_sql = implode(' ', $cases);
        $order_item_ids_sql = implode(',', $order_item_ids);
        // 商品引当を1(OK)と引き当てした商品IDを更新するSQLを作成
        $sql = "
            UPDATE order_items
            SET 
                is_item_allocated = 1,
                allocated_{$item_type}_id = CASE order_item_id
                    {$case_sql}
                END
            WHERE order_item_id IN ({$order_item_ids_sql})
        ";
        // SQLを実行
        DB::statement($sql);
    }

    // 受注商品構成を追加
    public function createOrderItemComponent($items, $item_type)
    {
        // 追加する情報を格納する配列を初期化
        $create_data = [];
        // 単品商品の場合
        if($item_type === 'item'){
            // 商品の分だけループ処理
            foreach($items as $item){
                // 追加する情報を配列に格納
                $create_data[] = [
                    'order_item_id'                 => $item->order_item_id,
                    'unallocated_quantity'          => $item->order_quantity,
                    'allocated_component_item_id'   => $item->matched_item_id,
                    'ship_quantity'                 => $item->order_quantity,
                ];
            }
        }
        // セット商品の場合
        if($item_type === 'set_item'){
            // 商品の分だけループ処理
            foreach($items as $item){
                // セット商品の構成情報を取得
                $set_item_details = SetItemDetail::getSpecifyBySetItemId($item->matched_set_item_id)->get();
                // 構成情報の分だけループ処理
                foreach($set_item_details as $set_item_detail){
                    // 追加する情報を配列に格納
                    $create_data[] = [
                        'order_item_id'                 => $item->order_item_id,
                        'unallocated_quantity'          => $item->order_quantity * $set_item_detail->component_quantity,
                        'allocated_component_item_id'   => $set_item_detail->component_item_id,
                        'ship_quantity'                 => $item->order_quantity * $set_item_detail->component_quantity,
                    ];
                }
            }
        }
        // 追加
        OrderItemComponent::upsert($create_data, 'order_item_component_id');
    }
}