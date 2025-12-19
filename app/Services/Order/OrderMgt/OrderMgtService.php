<?php

namespace App\Services\Order\OrderMgt;

// モデル
use App\Models\Order;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;

class OrderMgtService
{
    // 表示する注文ステータス毎の情報を取得
    public function getDispStatusInfo()
    {
        // 情報を格納する配列をセット
        $disp_statuses = [];
        // 表示対象となる注文ステータスを取得
        $order_statuses = OrderStatusEnum::getOrderMgtDispStatus();
        // 注文ステータスの分だけループ処理
        foreach($order_statuses as $order_status_id => $order_status){
            // ステータスの受注数を取得
            $order_count = Order::where('order_status_id', $order_status_id)->count();
            // 情報を格納
            $disp_statuses[] = [
                'order_status_id' => $order_status_id,
                'order_status' => $order_status,
                'order_count' => $order_count,
            ];
        }
        return $disp_statuses;
    }

    // 受注結合できる対象を取得
    public function getOrderMergeTarget()
    {
        // 注文ステータスが「出荷待ち」の受注で請求先名をカウントし、カウント数が2以上のものを取得
        $order_merge_bill_names = Order::where('order_status_id', OrderStatusEnum::SHUKKA_MACHI)
                                    ->select('bill_name', DB::raw('COUNT(*) as count'))
                                    ->groupBy('bill_name')
                                    ->having('count', '>', 1)
                                    ->pluck('bill_name');
        // 請求先名を条件に受注管理IDを取得
        $order_merge_targets = Order::where('order_status_id', OrderStatusEnum::SHUKKA_MACHI)
                                ->whereIn('bill_name', $order_merge_bill_names)
                                ->select('order_control_id')
                                ->pluck('order_control_id')
                                ->toArray();
        return $order_merge_targets;
    }
}