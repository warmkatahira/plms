<?php

namespace App\Services\Item\SetItem;

// モデル
use App\Models\SetItem;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\DB;

class SetItemSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_set_item_code',
            'search_set_item_name',
        ]);
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_set_item_code' => $request->search_set_item_code]);
            session(['search_set_item_name' => $request->search_set_item_name]);
        }
    }

    // 検索結果を取得
    public function getSearchResult($query)
    {
        // クエリをセット
        $query = $query->with('set_item_details.item');
        // セット商品コードの条件がある場合
        if(session('search_set_item_code') != null){
            // 条件を指定して取得
            $query = $query->where('set_item_code', 'LIKE', '%'.session('search_set_item_code').'%');
        }
        // セット商品名の条件がある場合
        if(session('search_set_item_name') != null){
            // 条件を指定して取得
            $query = $query->where('set_item_name', 'LIKE', '%'.session('search_set_item_name').'%');
        }
        // 並び替えを実施
        return $query->orderBy('set_items.set_item_id', 'asc');
    }
}