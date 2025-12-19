<?php

namespace App\Http\Controllers\API\Makeshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Item;
// 列挙
use App\Enums\API\MakeshopEnum;
// その他
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class UpdateProductQuantityController extends Controller
{
    public function update()
    {
        $timestamp = time();

        // 在庫を更新する商品情報を格納する配列を初期化
        $items = [];
        // 在庫を更新する商品情報を格納
        $items[] = [
            'productKeyType' => 1,
            'customCode' => ' ',
            'quantityCalculationType' => 1,
            'quantity' => 0,
        ];


        $response = Http::withHeaders([
            'Authorization' => MakeshopEnum::ACCESS_TOKEN,
            'Content-Type' => 'application/json',
            'X-API-Key' => MakeshopEnum::API_KEY,
            'X-Timestamp' => $timestamp,
        ])->post(MakeshopEnum::ENDPOINT, [
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
                    'productQuantities' => $items
                ],
            ],
        ]);
        // JSONを配列として取得
        $data = $response->json();
        dd($data);
    }
}