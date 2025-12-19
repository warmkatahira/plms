<?php

namespace App\Services\Item\ItemMallMapping;

// モデル
use App\Models\Item;
use App\Models\SetItem;
use App\Models\Mall;
// その他
use Illuminate\Support\Facades\DB;

class ItemMallMappingSearchService
{
    // 検索に使用するクエリを取得
    public function getSearchQueryItem()
    {
        // モールを取得
        $malls = Mall::getAll()->get();
        // itemsテーブルの全カラムを選択対象に追加
        $selects = ['items.*'];
        // モールの分だけループ処理
        foreach($malls as $mall){
            // 紐付いていれば「1」、紐付いていなければNull
            $selects[] = DB::raw("
                MAX(CASE WHEN item_mall.mall_id = {$mall->mall_id} THEN 1 ELSE NULL END) AS mall_id_{$mall->mall_id}
            ");
            // 紐付いていれば「mall_item_code」、紐付いていなければNull
            $selects[] = DB::raw("
                MAX(CASE WHEN item_mall.mall_id = {$mall->mall_id} THEN item_mall.mall_item_code ELSE NULL END) AS mall_item_code_{$mall->mall_id}
            ");
            // 紐付いていれば「mall_variation_code」、紐付いていなければNull
            $selects[] = DB::raw("
                MAX(CASE WHEN item_mall.mall_id = {$mall->mall_id} THEN item_mall.mall_variation_code ELSE NULL END) AS mall_variation_code_{$mall->mall_id}
            ");
        }
        // item_mallテーブルと結合し、1商品 = 1行になるよう集計
        return Item::leftJoin('item_mall', 'items.item_id', 'item_mall.item_id')
                    ->select($selects)
                    ->groupBy('items.item_id');
    }

    // 検索に使用するクエリを取得
    public function getSearchQuerySetItem()
    {
        // モールを取得
        $malls = Mall::getAll()->get();
        // set_itemsテーブルの全カラムを選択対象に追加
        $selects = ['set_items.*'];
        // モールの分だけループ処理
        foreach($malls as $mall){
            // 紐付いていれば「1」、紐付いていなければNull
            $selects[] = DB::raw("
                MAX(CASE WHEN set_item_mall.mall_id = {$mall->mall_id} THEN 1 ELSE NULL END) AS mall_id_{$mall->mall_id}
            ");
            // 紐付いていれば「mall_set_item_code」、紐付いていなければNull
            $selects[] = DB::raw("
                MAX(CASE WHEN set_item_mall.mall_id = {$mall->mall_id} THEN set_item_mall.mall_set_item_code ELSE NULL END) AS mall_set_item_code_{$mall->mall_id}
            ");
        }
        // set_item_mallテーブルと結合し、1商品 = 1行になるよう集計
        return SetItem::leftJoin('set_item_mall', 'set_items.set_item_id', 'set_item_mall.set_item_id')
                    ->select($selects)
                    ->groupBy('set_items.set_item_id');
    }
}