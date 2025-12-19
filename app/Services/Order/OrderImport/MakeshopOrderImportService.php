<?php

namespace App\Services\Order\OrderImport;

// モデル
use App\Models\OrderCategory;
use App\Models\Prefecture;
// サービス
use App\Services\API\Makeshop\MakeshopOrderService;
use App\Services\Common\ImportErrorCreateService;
use App\Services\Order\OrderImport\OrderImportValidationService;
// 列挙
use App\Enums\OrderStatusEnum;
// 例外
use App\Exceptions\OrderImportException;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;

class MakeshopOrderImportService
{
    // order_importsテーブルへ追加する受注データを配列に格納（同時にバリデーションも実施）
    public function setOrderArray($request, $nowDate, $order_category_id)
    {
        // インスタンス化
        $MakeshopOrderService       = new MakeshopOrderService;
        $OrderImportValidationService  = new OrderImportValidationService;
        // 注文情報をメイクショップから取得
        $orders = $MakeshopOrderService->getOrder($request);
        // テーブルへ追加する情報を格納する配列を初期化
        $order_create_data = [];
        // バリデーションエラーを格納する配列を初期化
        $validation_error   = [];
        // 注文の分だけループ
        foreach($orders as $order){
            // 配送先の分だけループ処理
            foreach($order['配送情報'] as $delivery){
                // 配送先郵便番号をXXX-XXXXの形式に変換して変数に格納
                $ship_postal_code = substr(str_replace("-", "", $delivery['配送先郵便番号']), 0, 3).'-'.substr(str_replace("-", "", $delivery['配送先郵便番号']), 3);
                // 商品の分だけループ処理
                foreach($delivery['商品情報'] as $item){
                    // 追加先テーブルのカラム名に合わせて配列を整理
                    $param = [
                        'order_import_date'         => $nowDate->toDateString(),
                        'order_import_time'         => $nowDate->toTimeString(),
                        'order_status_id'           => OrderStatusEnum::KAKUNIN_MACHI,
                        'mall_shipping_method'      => $delivery['配送方法ID'],
                        'desired_delivery_date'     => $delivery['配送希望日'],
                        'desired_delivery_time'     => $delivery['配送希望時間'],
                        'order_no'                  => $order['注文番号'],
                        'order_date'                => CarbonImmutable::parse($order['注文日時'])->toDateString(),
                        'order_time'                => CarbonImmutable::parse($order['注文日時'])->toTimeString(),
                        'buyer_name'                => $order['注文者名'],
                        'ship_name'                 => $delivery['配送先名'],
                        'ship_postal_code'          => $ship_postal_code,
                        'ship_province_name'        => Prefecture::extractPrefecture($delivery['配送先住所']),
                        'ship_address'              => $delivery['配送先住所'],
                        'ship_tel'                  => $delivery['配送先電話番号'],
                        'ship_id'                   => $delivery['配送先ID'],
                        'shipping_fee'              => $delivery['送料'],
                        'payment_amount'            => $order['注文合計金額_注文番号単位'],
                        'order_message'             => $delivery['配送備考'],
                        'order_item_system_code'    => $item['システム商品コード'],
                        'order_item_code'           => $item['バリエーション独自コード'] ?: $item['独自商品コード'],
                        'order_item_name'           => $item['商品名'].$item['バリエーション名'],
                        'order_quantity'            => $item['数量'],
                        'order_item_price'          => $item['商品価格'],
                        'order_category_id'         => $order_category_id,
                        'order_control_id_seq'      => $order['注文番号'].'_'.$delivery['配送先ID'],
                    ];
                    // 値が空であれば、nullを格納する
                    $param = array_map(function ($value){
                        return $value === "" ? null : $value;
                    }, $param);
                    // バリデーション処理
                    $message = $OrderImportValidationService->validation($order['注文番号'], $param);
                    // エラーメッセージがある場合
                    if(!is_null($message)){
                        // バリデーションエラーを配列に格納
                        $validation_error[] = array_combine(['注文番号', 'エラー内容'], $message);
                    }
                    // テーブルへ追加する情報を格納
                    $order_create_data[] = $param;
                }
            }
        }
        // バリデーションエラーにnull以外があれば、エラー情報を出力
        if(count(array_filter($validation_error)) != 0){
            // インスタンス化
            $ImportErrorCreateService   = new ImportErrorCreateService;
            // エラー情報のファイルを作成
            $error_file_name = $ImportErrorCreateService->createImportError('受注取込エラー', $validation_error, $nowDate, ['注文番号']);
            throw new OrderImportException("データが正しくないため、取り込みできませんでした。", null, null, $error_file_name);
        }
        return $order_create_data;
    }
}