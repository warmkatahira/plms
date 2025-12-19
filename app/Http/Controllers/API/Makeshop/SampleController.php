<?php

namespace App\Http\Controllers\API\Makeshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\API\Makeshop\ItemService;
use App\Services\API\Makeshop\MakeshopApiCommonService;
// モデル
use App\Models\Item;
// 列挙
use App\Enums\API\MakeshopEnum;
// その他
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class SampleController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'API']);
        return view('api.index')->with([
        ]);
    }

    public function updateOrderAttribute()
    {
        // 配列を初期化
        $update_orders = [];
        // 更新する情報を配列に格納
        $update_orders[] = [
            'displayOrderNumber'        => 'P184655728930017801',
            'deliveryRequestInfos'      => [[
                'slipNumber'            => '12345',
                'deliveryCompanyCode'   => '003',
            ]],
        ];
        // インスタンス化
        $MakeshopApiCommonService = new MakeshopApiCommonService;
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
                    'updateOrderAttributes' => $update_orders
                ],
            ],
        ]);
        // レスポンス(JSON)を配列として取得
        $data = $response->json();
        // エラーをチェック
        $errors = $this->checkError($data);
        dd($errors);
    }

    // エラーをチェック
    public function checkError($data)
    {
        // エラーを格納する配列を初期化
        $errors = [];
        // エラーが発生している場合
        if(isset($data['errors'][0]['message'])){
            // エラーを配列に格納
            return [
                'message' => $data['errors'][0]['message'],
            ];
        }
        // 結果の分だけループ処理
        foreach($data['data']['updateOrderAttribute']['updateOrderAttributeResults'] as $result){
            // メッセージがnull以外の場合
            if(!is_null($result['message'])){
                // エラーを配列に格納
                $errors[] = [
                    'order_no'  => $result['displayOrderNumber'],
                    'message'   => $result['message'],
                ];
            }
        }
        return $errors;
    }

    public function getShopDeliverySetting()
    {
        $timestamp = time();
        $response = Http::withHeaders([
            'Authorization' => MakeshopEnum::ACCESS_TOKEN,
            'Content-Type' => 'application/json',
            'X-API-Key' => MakeshopEnum::API_KEY,
            'X-Timestamp' => $timestamp,
        ])->post(MakeshopEnum::ENDPOINT, [
            'query' => 'query getShopDeliverySetting { getShopDeliverySetting { delivery { settingDeliveryMethods { settingName settingDisplay settingShippingFee settingAreaShippingFees { areaName feeByArea } } } } }',
        ]);
        // JSONを配列として取得
        $data = $response->json();
        dd($data);
    }

    public function searchProduct()
    {
        $data = $this->getProduct(1);
        dd($data);
    }

    public function searchProductQuantity()
    {
        $timestamp = time();
        $response = Http::withHeaders([
            'Authorization' => MakeshopEnum::ACCESS_TOKEN,
            'Content-Type' => 'application/json',
            'X-API-Key' => MakeshopEnum::API_KEY,
            'X-Timestamp' => $timestamp,
        ])->post(MakeshopEnum::ENDPOINT, [
            'query' => 'query SearchProductQuantityRequest($input: SearchProductQuantityRequest!) {
                searchProductQuantity(input: $input) {
                    productQuantities {
                        systemCode
                        customCode
                        productName
                        quantity
                        variationQuantities {
                            variationQuantity
                            variationCustomCode
                        }
                    }
                    page
                    limit
                }
            }',
            'variables' => [
                'input' => [
                    'page' => 1,
                    'limit' => MakeshopEnum::INPUT_LIMIT,
                    "systemCodes" => "000000000084",
                ],
            ],
        ]);
        // JSONを配列として取得
        $data = $response->json();
        dd($data);
    }

    public function product_sync()
    {
        // 商品情報を格納する配列を初期化
        $items = [];
        // 商品情報を取得(商品数を取得するため)
        $data = $this->getProduct(1);
        // APIを送信するページ数を取得
        $total_page = ceil($data['data']['searchProduct']['searchedCount'] / MakeshopEnum::INPUT_LIMIT);
        // ページ数の分だけループ処理
        for($i = 1; $i <= $total_page; $i++){
            // 指定したページの商品情報を取得
            $data = $this->getProduct($i);
            // 配列に商品情報を追加
            $items = $this->setArrayProduct($data, $items);
        }
        // 一時テーブルを作成
        DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_items');
        DB::statement('
            CREATE TEMPORARY TABLE temp_items (
                item_code VARCHAR(255) NOT NULL,
                item_jan_code VARCHAR(13) NULL,
                item_name VARCHAR(255) NULL,
                item_category VARCHAR(100) NULL,
                is_stock_managed TINYINT(1) NULL,
                item_image_file_name VARCHAR(50) NOT NULL
            )
        ');
        // 一時テーブルにAPIで取得した商品情報を追加
        DB::table('temp_items')->insert($items);
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 既存の更新
        DB::table('items')
            ->join('temp_items', 'items.item_code', '=', 'temp_items.item_code')
            ->update([
                'items.item_jan_code' => DB::raw('temp_items.item_jan_code'),
                'items.item_name' => DB::raw('temp_items.item_name'),
                'items.item_category' => DB::raw('temp_items.item_category'),
                'items.item_image_file_name' => DB::raw('temp_items.item_image_file_name'),
                'items.updated_at' => $nowDate
            ]);
        // 新規追加
        DB::statement('
            INSERT INTO items (item_code, item_jan_code, item_name, item_category, is_stock_managed, item_image_file_name, created_at, updated_at)
            SELECT t.item_code, t.item_jan_code, t.item_name, t.item_category, t.is_stock_managed, t.item_image_file_name, NOW(), NOW()
            FROM temp_items t
            LEFT JOIN items i ON i.item_code = t.item_code
            WHERE i.item_code IS NULL
        ');
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '商品の同期が完了しました。',
        ]);
    }
}