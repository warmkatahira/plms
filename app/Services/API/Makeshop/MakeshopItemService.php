<?php

namespace App\Services\API\Makeshop;

// モデル
use App\Models\Item;
// サービス
use App\Services\API\Makeshop\MakeshopApiCommonService;
// 列挙
use App\Enums\API\MakeshopEnum;
// その他
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class MakeshopItemService
{
    // 商品情報を取得
    public function getItem()
    {
        // 商品情報を格納する配列を初期化
        $items = [];
        // 指定したページの商品情報を取得(商品数を取得するためなので「1」を指定している)
        $data = $this->getItemByPage(1);
        // APIを送信するページ数を取得(商品数 / 1ページで取得する商品数)
        $total_page = ceil($data['data']['searchProduct']['searchedCount'] / MakeshopEnum::INPUT_LIMIT);
        // ページ数の分だけループ処理
        for($i = 1; $i <= $total_page; $i++){
            // 指定したページの商品情報を取得
            $data = $this->getItemByPage($i);
            // 商品情報を配列に追加
            $items = $this->setArrayItem($data, $items);
        }
        return $items;
    }

    // 指定したページの商品情報を取得
    public function getItemByPage($page)
    {
        // インスタンス化
        $MakeshopApiCommonService = new MakeshopApiCommonService;
        // レスポンスを取得
        $response = $MakeshopApiCommonService->request([
            'query' => 'query searchProduct($input: SearchProductRequest!){ 
                searchProduct(input: $input) { 
                    products { 
                        uid systemCode customCode janCode productName sellPrice maxImageUrl variationShowType
                        subImages {
                            subImageId
                            url
                        }
                        variations {
                            variation1ItemName
                            variation2ItemName
                            subImageId
                            productSystemCode
                            variationCustomCode
                            janCode
                        }
                        categories {
                            categoryName
                        }
                    }
                    searchedCount
                }
            }',
            'variables' => [
                'input' => [
                    'page'  => $page,
                    'limit' => MakeshopEnum::INPUT_LIMIT,
                    
                ],
            ],
            'operationName' => 'searchProduct',
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

    // 商品情報を配列に追加
    public function setArrayItem($data, $items)
    {
        // 商品の分だけループ処理
        foreach($data['data']['searchProduct']['products'] as $product){
            // バリエーション商品の場合
            if($product['variationShowType'] !== 0){
                // バリエーション情報を格納する配列を初期化
                $variations = [];
                // サブ画像情報を取得
                $subImages = $product['subImages'];
                // バリエーションの分だけループ処理
                foreach($product['variations'] as $variation){
                    // subImageIdが一致する要素を取得
                    $subImage = collect($subImages)->firstWhere('subImageId', $variation['subImageId']);
                    // バリエーション情報を配列に格納
                    $variations[] = [
                        'variationCustomCode'   => trim($variation['variationCustomCode']),
                        'janCode'               => $variation['janCode'],
                        'variationItemName'     => $variation['variation1ItemName'].$variation['variation2ItemName'],
                        'subImageId'            => $variation['subImageId'],
                        'subImageUrl'           => $subImage ? explode('?', $subImage['url'])[0] : null,
                        'subImageFileName'      => $subImage ? basename(explode('?', $subImage['url'])[0]) : 'no_image.png',
                    ];
                }
                // バリエーションの分だけループ処理
                foreach($variations as $variation){
                    $items[$product['systemCode']][$variation['variationCustomCode']] = [
                        'system_code'           => $product['systemCode'],
                        'item_code'             => $variation['variationCustomCode'],
                        'item_jan_code'         => $variation['janCode'],
                        'item_name'             => $product['productName'].' '.$variation['variationItemName'],
                        'item_category_1'       => $product['categories'][0]['categoryName'] ?? null,
                        'item_image_file_name'  => $variation['subImageFileName'],
                        'item_image_file_path'  => $variation['subImageUrl'],
                    ];
                }
            }
            // 非バリエーション商品の場合
            if($product['variationShowType'] == 0){
                $items[$product['systemCode']][$product['customCode']] = [
                    'system_code'           => $product['systemCode'],
                    'item_code'             => $product['customCode'],
                    'item_jan_code'         => $product['janCode'],
                    'item_name'             => $product['productName'],
                    'item_category_1'       => $product['categories'][0]['categoryName'] ?? null,
                    'item_image_file_name'  => !empty($product['maxImageUrl']) ? basename($product['maxImageUrl']) : 'no_image.png',
                    'item_image_file_path'  => $product['maxImageUrl'],
                ];
            }
        }
        return $items;
    }

    // 商品情報をitemsテーブルへ追加・更新
    public function updateItem($items)
    {
        // 一時テーブルを作成
        DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_items');
        DB::statement('
            CREATE TEMPORARY TABLE temp_items (
                item_code VARCHAR(255) NOT NULL,
                item_jan_code VARCHAR(13) NULL,
                item_name VARCHAR(255) NULL,
                item_category_1 VARCHAR(100) NULL,
                is_stock_managed TINYINT(1) NULL,
                item_image_file_name VARCHAR(50) NOT NULL,
                item_image_file_path VARCHAR(2048) NULL
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
                'items.item_jan_code'           => DB::raw('temp_items.item_jan_code'),
                'items.item_name'               => DB::raw('temp_items.item_name'),
                'items.item_category_1'         => DB::raw('temp_items.item_category_1'),
                'items.item_image_file_name'    => DB::raw('temp_items.item_image_file_name'),
                'items.updated_at'              => $nowDate
            ]);
        // 新規追加
        DB::statement('
            INSERT INTO items (item_code, item_jan_code, item_name, item_category_1, is_stock_managed, item_image_file_name, created_at, updated_at)
            SELECT t.item_code, t.item_jan_code, t.item_name, t.item_category_1, t.is_stock_managed, t.item_image_file_name, NOW(), NOW()
            FROM temp_items t
            LEFT JOIN items i ON i.item_code = t.item_code
            WHERE i.item_code IS NULL
        ');
    }
}