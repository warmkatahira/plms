<?php

namespace App\Services\API\Makeshop;

// モデル
use App\Models\Order;
// 列挙
use App\Enums\MallEnum;
use App\Enums\API\MakeshopEnum;
// サービス
use App\Services\API\Makeshop\MakeshopApiCommonService;
// 例外
use App\Exceptions\ShippingWorkEndException;

class MakeshopCompleteShipmentService
{
    // メイクショップからの注文の出荷処理
    public function completeShipment($order_control_ids)
    {
        // 出荷完了対象の中からメイクショップからの注文を取得
        $orders = $this->getCompleteShipmentOrder($order_control_ids);
        // 対象が存在しない場合
        if($orders->isEmpty()){
            // 処理をスキップ
            return;
        }
        // 更新する情報を配列に格納
        $array = $this->setArrayUpdateInfo($orders);
        // インスタンス化
        $MakeshopApiCommonService = new MakeshopApiCommonService;
        // エラーを格納する配列を初期化
        $errors = [];
        // 伝票番号と配送業者を更新
        $errors = $this->updateOrderAttribute($MakeshopApiCommonService, $array['update_order_attribute'], $errors);
        // 配送ステータスを更新
        $errors = $this->updateOrderDeliveryStatus($MakeshopApiCommonService, $array['update_order_delivery_status'], $errors);
        // エラーがある場合
        if(!empty($errors)){
            // レスポンスエラーをファイルに出力
            $file_name = $MakeshopApiCommonService->exportResponseError($errors, '【Makeshop】出荷完了エラー');
            // エラーを返して、処理を中断
            throw new ShippingWorkEndException('モール側の出荷完了が失敗したため、出荷完了を中断しました。', $orders->count(), 0, $file_name);
        }
        return $errors;
    }

    // 出荷完了対象の中からメイクショップからの注文を取得
    public function getCompleteShipmentOrder($order_control_ids)
    {
        return Order::join('order_categories', 'order_categories.order_category_id', 'orders.order_category_id')
                        ->whereIn('orders.order_control_id', $order_control_ids)
                        ->where('mall_id', MallEnum::MAKESHOP)
                        ->get();
    }

    // 更新する情報を配列に格納
    public function setArrayUpdateInfo($orders)
    {
        // 更新情報を格納する配列を初期化
        $update_order_attribute = [];
        $update_order_delivery_status = [];
        // 受注の分だけループ処理
        foreach($orders as $order){
            // 伝票番号と配送業者を更新する情報を配列に格納
            $update_order_attribute[] = [
                'displayOrderNumber'        => $order->order_no,
                'deliveryRequestInfos'      => [
                    [
                        'deliveryId'            => $order->ship_id,
                        'slipNumber'            => $order->tracking_no,
                        'deliveryCompanyCode'   => MakeshopEnum::delivery_company_code_get($order->shipping_method_id),
                    ]
                ]
            ];
            // 配送ステータスを更新する情報を配列に格納
            $update_order_delivery_status[] = [
                'displayOrderNumber'    => $order->order_no,
                'deliveryId'            => $order->ship_id,
                'deliveryStatus'        => 'NOT_YET', // COMPLETED = 配送完了
            ];
        }
        return compact('update_order_attribute', 'update_order_delivery_status');
    }

    // 伝票番号と配送業者を更新
    public function updateOrderAttribute($MakeshopApiCommonService, $update_info, $errors)
    {
        // レスポンスを取得
        $response = $MakeshopApiCommonService->request([
            'query' => 'mutation updateOrderAttribute($input: UpdateOrderAttributeRequest!) {
                updateOrderAttribute(input: $input) {
                    updateOrderAttributeResults {
                        displayOrderNumber
                        message
                    }
                }
            }',
            'variables' => [
                'input' => [
                    'updateOrderAttributes' => $update_info
                ],
            ],
        ]);
        // レスポンス(JSON)を配列として取得
        $response_array = $response->json();
        // レスポンスエラーをチェック
        return $MakeshopApiCommonService->checkResponseError($response_array, 'updateOrderAttribute', 'updateOrderAttributeResults', $errors);
    }

    // 配送ステータスを更新
    public function updateOrderDeliveryStatus($MakeshopApiCommonService, $update_info, $errors)
    {
        // レスポンスを取得
        $response = $MakeshopApiCommonService->request([
            'query' => 'mutation updateOrderDeliveryStatus($input: UpdateOrderDeliveryStatusRequest!) {
                updateOrderDeliveryStatus(input: $input) {
                    updateOrderDeliveryStatusResults {
                        displayOrderNumber
                        deliveryId
                        message
                    }
                }
            }',
            'variables' => [
                'input' => [
                    'updateOrderDeliveryStatusRequests' => $update_info
                ],
            ],
        ]);
        // レスポンス(JSON)を配列として取得
        $response_array = $response->json();
        // レスポンスエラーをチェック
        return $MakeshopApiCommonService->checkResponseError($response_array, 'updateOrderDeliveryStatus', 'updateOrderDeliveryStatusResults', $errors);
    }
}