<?php

namespace App\Services\API\Makeshop;

// モデル
use App\Models\Stock;
use App\Models\ItemMall;
use App\Models\ApiHistory;
// サービス
use App\Services\API\Makeshop\MakeshopApiCommonService;
// 列挙
use App\Enums\API\MakeshopEnum;
use App\Enums\MallEnum;
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class MakeshopStockUpdateService
{
    // 在庫更新対象を取得
    public function getStockUpdateItem()
    {
        // 在庫更新対象を格納する配列を初期化
        $stocks = [];
        // item_mallテーブルでメイクショップのレコードのみを取得し、item_mall単位でavailable_stock(有効在庫数)を合計
        $item_malls = ItemMall::getSpecifyByMallId(MallEnum::MAKESHOP)
                        ->join('items', 'items.item_id', 'item_mall.item_id')
                        ->join('stocks', 'stocks.item_id', 'items.item_id')
                        ->select(
                            'item_mall.mall_item_code',
                            'item_mall.mall_variation_code',
                            DB::raw('SUM(stocks.available_stock) as available_stock_sum'),
                        )
                        ->groupBy('item_mall.item_id', 'item_mall.mall_item_code', 'item_mall.mall_variation_code')
                        ->get();
        // レコードが取得できていない場合
        if($item_malls->isEmpty()){
            // 処理をスキップ
            return;
        }
        // item_mallsのレコードの分だけループ処理
        foreach($item_malls as $item_mall){
            // 非バリエーション商品の場合
            if(is_null($item_mall->mall_variation_code)){
                // 配列に追加
                $stocks['non_variation'][] = [
                    'productKeyType'            => 0,
                    'systemCode'                => $item_mall->mall_item_code,
                    'quantityCalculationType'   => 1,
                    'quantity'                  => -1,
                ];
            }
            // バリエーション商品の場合
            if(!is_null($item_mall->mall_variation_code)){
                // 配列に追加
                $stocks['variation'][] = [
                    'productKeyType'            => 0,
                    'systemCode'                => $item_mall->mall_item_code,
                    'variationQuantityDetails'  => [
                        'variationKeyType'          => 1,
                        'variationCustomCode'       => $item_mall->mall_variation_code,
                        'quantityCalculationType'   => 1,
                        'quantity'                  => -1,
                    ],
                ];
            }
        }
        return $stocks;
    }

    // 在庫を更新
    public function updateStock($stocks)
    {
        // インスタンス化
        $MakeshopApiCommonService = new MakeshopApiCommonService;
        // エラーを格納する配列を初期化
        $errors = [];
        // 非バリエーション商品がある場合
        if(!empty($stocks['non_variation'])){
            // 在庫更新(非バリエーション商品)
            $errors = $this->updateStockForNonVariation($MakeshopApiCommonService, $errors, $stocks['non_variation']);
        }
        // バリエーションの商品がある場合
        if(!empty($stocks['variation'])){
            // 在庫更新(バリエーション商品)
            $errors = $this->updateStockForVariation($MakeshopApiCommonService, $errors, $stocks['variation']);
        }
        return $errors;
    }

    // 在庫更新(非バリエーション商品)
    public function updateStockForNonVariation($MakeshopApiCommonService, $errors, $stocks)
    {
        // レスポンスを取得
        $response = $MakeshopApiCommonService->request([
            'query' => 'mutation updateProductQuantity($input: UpdateProductQuantityRequest!) {
                updateProductQuantity(input: $input) {
                    failResult {
                        systemCode
                        customCode
                        message
                    }
                }
            }',
            'variables' => [
                'input' => [
                    'productQuantities' => $stocks
                ],
            ],
        ]);
        // レスポンス(JSON)を配列として取得
        $response_array = $response->json();
        // エラーをチェック
        return $MakeshopApiCommonService->checkResponseError($response_array, 'updateProductQuantity', 'failResult', $errors);
    }

    // 在庫更新(バリエーション商品)
    public function updateStockForVariation($MakeshopApiCommonService, $errors, $stocks)
    {
        // レスポンスを取得
        $response = $MakeshopApiCommonService->request([
            'query' => 'mutation updateVariationQuantity($input: UpdateVariationQuantityRequest!) {
                updateVariationQuantity(input: $input) {
                    failResult {
                        systemCode
                        customCode
                        variation1ItemId
                        variation2ItemId
                        variationCustomCode
                        message
                    }
                }
            }',
            'variables' => [
                'input' => [
                    'variationQuantities' => $stocks
                ],
            ],
        ]);
        // レスポンス(JSON)を配列として取得
        $response_array = $response->json();
        // エラーをチェック
        return $MakeshopApiCommonService->checkResponseError($response_array, 'updateVariationQuantity', 'failResult', $errors);
    }
}