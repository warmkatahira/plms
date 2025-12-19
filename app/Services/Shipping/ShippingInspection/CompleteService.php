<?php

namespace App\Services\Shipping\ShippingInspection;

// モデル
use App\Models\Order;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\OrderStatusEnum;

class CompleteService
{
    // ordersテーブルを更新
    public function updateInspectionResultForOrder($order_control_id)
    {
        // 受注を取得してロック
        $order = Order::getSpecifyByOrderControlId($order_control_id)->where('order_status_id', OrderStatusEnum::SAGYO_CHU)->lockForUpdate()->first();
        // 受注が取得できていない場合
        if(!$order){
            throw new \RuntimeException('出荷検品が正常に完了できませんでした。');
        }
        // 出荷検品と出荷検品日時を更新
        $order->update([
            'is_shipping_inspection_complete' => 1,
            'shipping_inspection_date' => CarbonImmutable::now(),
        ]);
    }
}