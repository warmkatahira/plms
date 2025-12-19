<?php

namespace App\Services\Shipping\ShippingWorkEnd;

// サービス
use App\Services\API\Makeshop\MakeshopCompleteShipmentService;

class MallShipmentService
{
    // モール側の出荷処理を実施
    public function completeMallShipment($order_control_ids)
    {
        // インスタンス化
        $MakeshopCompleteShipmentService = new MakeshopCompleteShipmentService;
        // メイクショップからの注文の出荷処理
        $MakeshopCompleteShipmentService->completeShipment($order_control_ids);
    }
}