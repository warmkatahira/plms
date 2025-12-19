<?php

namespace App\Services\Shipping\ShippingInspection;

// モデル
use App\Models\Order;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;

class TrackingNoCheckService
{
    // 配送伝票番号が一致しているか確認
    public function check($request)
    {
        // 送信されてきた問い合わせ番号から不要な文字を取り除く
        $tracking_no = str_replace(['d', 'D', 'a', 'A'], '', $request->tracking_no);
        // 送信されてきた受注管理IDでordersからレコードを取得
        $order = Order::getSpecifyByOrderControlId($request->order_control_id)->where('order_status_id', OrderStatusEnum::SAGYO_CHU)->first();
        // 配送伝票番号が一致しているか
        if($tracking_no != $order->tracking_no){
            return '配送伝票番号が一致しません。';
        }
        // 問題なければnullを返す
        return null;
    }

    // 検品する商品情報を取得
    public function getInspectionTarget($order_control_id)
    {
        // 検品対象の商品情報を取得
        return Order::getSpecifyByOrderControlId($order_control_id)
                    ->where('order_status_id', OrderStatusEnum::SAGYO_CHU)
                    ->where('order_item_components.is_shipping_inspection_required_snapshot', true)
                    ->join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                    ->join('order_item_components', 'order_item_components.order_item_id', 'order_items.order_item_id')
                    ->select('order_item_components.allocated_component_item_id as item_id', 'order_item_components.item_jan_code_snapshot as item_jan_code', 'order_item_components.item_name_snapshot as item_name', DB::raw('SUM(order_item_components.ship_quantity) as total_ship_quantity'))
                    ->groupBy('order_item_components.allocated_component_item_id', 'order_item_components.item_jan_code_snapshot', 'order_item_components.item_name_snapshot')
                    ->orderBy('order_item_components.allocated_component_item_id', 'asc')
                    ->get();
    }

    // セッションに商品情報を格納
    public function setInspectionTarget($inspection_targets)
    {
        // 検品の進捗状況を保持するセッションと配列をクリア
        session()->forget(['progress']);
        $data = [];
        // 商品情報の分だけループ処理
        foreach($inspection_targets as $inspection_target){
            // 商品情報を配列にセット
            $param = [
                'item_id'               => $inspection_target->item_id,
                'item_jan_code'         => $inspection_target->item_jan_code,
                'item_name'             => $inspection_target->item_name,
                'total_ship_quantity'   => $inspection_target->total_ship_quantity,
                'inspection_quantity'   => 0,
                'inspection_complete'   => false,
            ];
            // 配列に格納
            $data[$inspection_target->item_id] = $param;
        }
        // セッションへ格納
        session(['progress' => $data]);
    }
}