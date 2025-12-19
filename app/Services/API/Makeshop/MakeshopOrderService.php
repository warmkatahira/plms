<?php

namespace App\Services\API\Makeshop;

// モデル
// サービス
use App\Services\API\Makeshop\MakeshopApiCommonService;
// 列挙
use App\Enums\API\MakeshopEnum;
use App\Enums\MallEnum;
// その他
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class MakeshopOrderService
{
    // 注文情報をメイクショップから取得
    public function getOrder($request)
    {
        // 注文を取得する開始日時と終了日時を変数に格納
        $start_order_date = CarbonImmutable::parse($request->date_from . ' ' . $request->time_from)->format('YmdHi') . '00';
        $end_order_date = CarbonImmutable::parse($request->date_to . ' ' . $request->time_to)->format('YmdHi') . '59';
        // 注文情報を格納する配列を初期化
        $orders = [];
        // 指定したページの注文情報をメイクショップから取得(受注数を取得するためなので「1」を指定している)
        $data = $this->getOrderByPage(1, $start_order_date, $end_order_date);
        // APIを送信するページ数を取得(受注数 / 1ページで取得する商品数)
        $total_page = ceil($data['data']['searchOrder']['searchedCount'] / MakeshopEnum::INPUT_LIMIT);
        // ページ数の分だけループ処理
        for($i = 1; $i <= $total_page; $i++){
            // 指定したページの注文情報をメイクショップから取得
            $data = $this->getOrderByPage($i, $start_order_date, $end_order_date);
            // 注文情報を配列に格納
            $orders = $this->setArrayOrder($data, $orders);
        }
        return $orders;
    }

    // 指定したページの注文情報をメイクショップから取得
    public function getOrderByPage($page, $start_order_date, $end_order_date)
    {
        // インスタンス化
        $MakeshopApiCommonService = new MakeshopApiCommonService;
        // レスポンスを取得
        $response = $MakeshopApiCommonService->request([
            'query' => 'query searchOrder($input: SearchOrderRequest!){ 
                searchOrder(input: $input) { 
                    orders { 
                        displayOrderNumber orderDate senderName payMethod paymentStatusCode sumPrice message
                        deliveryInfos {
                            deliveryId
                            deliveryStatus
                            desiredDeliveryDate
                            desiredDeliveryTimezone
                            receiverName
                            receiverPost
                            receiverPrefecture
                            receiverAddress
                            receiverAddress2
                            receiverTel
                            shippingCharge
                            deliverySumPrice
                            wmsStatus
                            deliveryMessage
                            deliveryMethodIds
                            basketInfos {
                                productCode
                                productCustomCode
                                variationCustomCode
                                productName
                                variationName
                                amount
                                price
                                janCode
                            }
                        }
                    }
                    searchedCount
                }
            }',
            'variables' => [
                'input' => [
                    'page'              => $page,
                    'limit'             => MakeshopEnum::INPUT_LIMIT,
                    'deliveryStatus'    => 'N', // 「N」= 配送状態 未処理
                    'startPaymentDate'    => $start_order_date,
                    'endPaymentDate'      => $end_order_date,
                ],
            ],
        ]);
        // レスポンス(JSON)を配列として取得
        $data = $response->json();
        // エラーが発生している場合
        if(isset($data['errors'][0]['message'])){
            // エラーを返す
            throw new \RuntimeException($data['errors'][0]['message']);
        }
        return $data;
    }

    // 注文情報を配列に格納
    public function setArrayOrder($data, $orders)
    {
        // 注文の分だけループ処理
        foreach($data['data']['searchOrder']['orders'] as $order){
            // 配列に格納
            $orders[$order['displayOrderNumber']] = [
                '注文番号'                  => $order['displayOrderNumber'],
                '注文日時'                  => $order['orderDate'],
                '注文者名'                  => $order['senderName'],
                '決済方法'                  => $order['payMethod'],
                '決済状態コード'            => $order['paymentStatusCode'],
                '注文合計金額_注文番号単位' => $order['sumPrice'],
                '注文備考'                  => trim(explode('||', $order['message'])[0]),
                '配送情報'                  => [],
            ];
            // 配送先の分だけループ処理
            foreach($order['deliveryInfos'] as $delivery_index => $delivery_info){
                // 配列に格納
                $orders[$order['displayOrderNumber']]['配送情報'][$delivery_index] = [
                    '配送先ID'                  => $delivery_info['deliveryId'],
                    '配送状態'                  => $delivery_info['deliveryStatus'],
                    '配送希望日'                => $delivery_info['desiredDeliveryDate'] === '--' ? '' : $delivery_info['desiredDeliveryDate'],
                    '配送希望時間'              => $delivery_info['desiredDeliveryTimezone'],
                    '配送先名'                  => $delivery_info['receiverName'],
                    '配送先郵便番号'            => $delivery_info['receiverPost'],
                    '配送先都道府県'            => $delivery_info['receiverPrefecture'],
                    '配送先住所'                => $delivery_info['receiverPrefecture'].$delivery_info['receiverAddress'].$delivery_info['receiverAddress2'],
                    '配送先電話番号'            => $delivery_info['receiverTel'],
                    '送料'                      => $delivery_info['shippingCharge'],
                    '注文合計金額_配送先単位'   => $delivery_info['deliverySumPrice'],
                    '出荷指示ステータス'        => $delivery_info['wmsStatus'],
                    '配送備考'                  => trim(explode('||', $delivery_info['deliveryMessage'])[0]),
                    '配送方法ID'                => $delivery_info['deliveryMethodIds'],
                    '商品情報'                  => [],
                ];
                // 商品の分だけループ処理
                foreach($delivery_info['basketInfos'] as $basket_info){
                    // 配列に格納
                    $orders[$order['displayOrderNumber']]['配送情報'][$delivery_index]['商品情報'][] = [
                        'システム商品コード'        => $basket_info['productCode'],
                        '独自商品コード'            => $basket_info['productCustomCode'],
                        'バリエーション独自コード'  => $basket_info['variationCustomCode'],
                        '商品名'                    => $basket_info['productName'],
                        'バリエーション名'          => $basket_info['variationName'],
                        '数量'                      => $basket_info['amount'],
                        '商品価格'                  => $basket_info['price'],
                        'JANコード'                 => $basket_info['janCode'],
                    ];
                }
            }
        }
        return $orders;
    }
}