<?php

namespace App\Services\Shipping\OrderDocument;

// モデル
use App\Models\Order;
use App\Models\Stock;

class OrderDocumentService
{
    public function getIssueOrder($shipping_method_id, $start, $end)
    {
        // 指定された出荷グループ × 配送方法の受注を取得
        $orders = Order::where('shipping_group_id', session('search_shipping_group_id'))
                        ->where('orders.shipping_method_id', $shipping_method_id)
                        ->with('shipping_method')
                        ->with('shipping_group')
                        ->with('order_category.shipper')
                        ->with(['order_items' => function ($q) {
                            $q->with([
                                'item',
                                'set_item',
                                'order_item_components' => function ($q2) {
                                    $q2->orderBy('allocated_component_item_id', 'asc');
                                },
                            ]);
                        }])
                        ->select('orders.*')
                        ->orderBy('order_control_id', 'asc');
        // $startがnullでなければ、skipで飛ばして、takeで指定した数を取得
        if(!is_null($start)){
            // skipする数を取得
            $skip = $start - 1;
            // takeする数を取得
            $take = $end - $skip;
            $orders = $orders->skip($skip)->take($take);
        }
        return $orders->get();
    }

    public function getIssueOrderBySelectOrder($chk)
    {
        // 指定された受注管理IDの受注を取得
        return Order::whereIn('order_control_id', $chk)
                        ->with('shipping_method')
                        ->with('shipping_group')
                        ->with('order_category.shipper')
                        ->with(['order_items' => function ($q) {
                            $q->with([
                                'item',
                                'set_item',
                                'order_item_components' => function ($q2) {
                                    $q2->orderBy('allocated_component_item_id', 'asc');
                                },
                            ]);
                        }])
                        ->select('orders.*')
                        ->orderBy('order_control_id', 'asc')
                        ->get();
    }

    // 分割情報を取得
    public function getIssueRange($orders, $shipping_method_id)
    {
        // 1グループあたりの件数
        $chunk_size = 200;
        // 分割数を計算
        $chunk_count = ceil(count($orders) / $chunk_size);
        // 結果を格納する配列
        $ranges = [];
        // 分割範囲を生成
        for($i = 0; $i < $chunk_count; $i++){
            $start = $i * $chunk_size + 1;
            $end = min(($i + 1) * $chunk_size, count($orders));
            $ranges[] = [
                'shipping_method_id' => $shipping_method_id,
                'start' => $start,
                'end' => $end
            ];
        }
        return $ranges;
    }

    // 商品ロケーションを取得
    public function getItemLocation($order)
    {
        // 出荷倉庫IDを取得
        $shipping_base_id = $order->shipping_base_id;
        // 該当倉庫の商品ロケーションを取得
        return Stock::where('base_id', $shipping_base_id)
                            ->whereNotNull('item_location')
                            ->select('item_id', 'item_location')
                            ->pluck('item_location', 'item_id')
                            ->toArray();
    }

    // 作成可能か確認
    public function checkCreatable($chk)
    {
        // 選択されている注文の配送方法IDの重複を取り除く
        $shipping_methods = Order::whereIn('order_control_id', $chk)
                    ->select('shipping_method_id')
                    ->distinct()
                    ->get();
        // レコードが2以上の場合
        if($shipping_methods->count() > 1){
            throw new \RuntimeException('複数の配送方法が選択されています。');
        }
        // 選択されている注文の出荷グループIDの重複を取り除く
        $shipping_groups = Order::whereIn('order_control_id', $chk)
                    ->select('shipping_group_id')
                    ->distinct()
                    ->get();
        // レコードが2以上の場合
        if($shipping_groups->count() > 1){
            throw new \RuntimeException('複数の出荷グループが選択されています。');
        }
    }
}