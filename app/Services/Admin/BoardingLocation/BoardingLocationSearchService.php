<?php

namespace App\Services\Admin\BoardingLocation;

// モデル
use App\Models\BoardingLocation;
// その他
use Illuminate\Support\Facades\DB;

class BoardingLocationSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_is_active',
            'search_location_name',
        ]);
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
            session(['search_is_active' => '1']);
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_is_active' => $request->search_is_active]);
            session(['search_location_name' => $request->search_location_name]);
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = BoardingLocation::query();
        // 利用可否の条件がある場合
        if(session('search_is_active') != null){
            // 条件を指定して取得
            $query = $query->where('is_active', session('search_is_active'));
        }
        // 場所名の条件がある場合
        if(session('search_location_name') != null){
            // 条件を指定して取得
            $query = $query->where('location_name', 'LIKE', '%' . session('search_location_name') . '%');
        }
        // 並び替えを実施
        return $query->orderBy('sort_order', 'asc');
    }
}