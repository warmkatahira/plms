<?php

namespace App\Services\API\Makeshop;

// サービス
use App\Services\API\Makeshop\MakeshopApiCommonService;
// 列挙
use App\Enums\API\MakeshopEnum;
use App\Enums\MallEnum;
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class MakeshopOrderCancelService
{
    // 注文をキャンセル
    public function cancelOrder($orders)
    {
        // キャンセルする注文の情報を格納する配列を初期化
        $cancel_orders = [];
        // 注文の分だけループ処理
        foreach($orders as $order){
            // 配列に情報を格納
            $cancel_orders[] = [
                'displayOrderNumber'        => $order->order_no,
                'deliveryId'                => $order->ship_id,
                'isReturnStock'             => true,
                'isReturnPoint'             => true,
            ];
        }
        // インスタンス化
        $MakeshopApiCommonService = new MakeshopApiCommonService;
        // レスポンスを取得
        $response = $MakeshopApiCommonService->request([
            'query' => 'mutation cancelOrder($input: CancelOrderRequest!) {
                cancelOrder(input: $input) {
                    cancelOrderResults {
                        displayOrderNumber
                        deliveryId
                        message
                    }
                }
            }',
            'variables' => [
                'input' => [
                    'cancelOrderRequests' => $cancel_orders
                ],
            ],
        ]);
        // レスポンス(JSON)を配列として取得
        $data = $response->json();
        // エラーをチェック
        return $this->checkError($data);
    }

    // エラーをチェック
    public function checkError($data)
    {
        // エラーを格納する配列を初期化
        $errors = [];
        // エラーが発生している場合
        if(isset($data['errors'][0]['message'])){
            // エラーを配列に格納
            $errors[] = [
                'message' => $data['errors'][0]['message'],
            ];
        }
        // エラーが発生している場合
        if(!empty($data['data']['cancelOrder']['cancelOrderResults'])){
            // エラーの分だけループ処理
            foreach($data['data']['cancelOrder']['cancelOrderResults'] as $error){
                // エラーを配列に格納
                $errors[] = [
                    'order_no'  => $error['displayOrderNumber'],
                    'ship_id'   => $error['deliveryId'],
                    'message'   => $error['message'],
                ];
            }
        }
        return $errors;
    }

    // エラーをファイルに出力
    public function exportError($errors)
    {
        // エラーが無い場合
        if(empty($errors)){
            // 処理をスキップ
            return;
        }
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // チャンクサイズを設定
        $chunk_size = 500;
        // チャンクサイズ毎に分割
        $chunks = array_chunk($errors, $chunk_size);
        // ファイル名を設定
        $file_name = '【'.SystemEnum::getSystemTitle().'】注文キャンセルエラーデータ_' . $nowDate->isoFormat('Y年MM月DD日HH時mm分ss秒').'.csv';
        // 保存場所を設定
        $csvFilePath = storage_path('app/public/export/error/'.$file_name);
        // ヘッダ行を書き込む
        $header = ['注文番号', '配送先ID', 'メッセージ'];
        $csvContent = "\xEF\xBB\xBF" . implode(',', $header) . "\n";
        // チャンク毎のループ処理
        foreach($chunks as $chunk){
            // レコード毎のループ処理
            foreach($chunk as $item){
                // CSV形式で内容をセット
                $row = [
                    $item['order_no'],
                    $item['ship_id'],
                    $item['message'],
                ];
                // 書き込む
                $csvContent .= implode(',', $row) . "\n";
            }
        }
        // ファイルに出力
        file_put_contents($csvFilePath, $csvContent);
        return $file_name;
    }
}