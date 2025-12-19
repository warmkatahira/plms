<?php

namespace App\Services\API;

// モデル
use App\Models\ApiHistory;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\DB;

class ApiHistorySearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_mall_id',
            'search_api_action_id',
            'search_api_status_id',
        ]);
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_mall_id' => $request->search_mall_id]);
            session(['search_api_action_id' => $request->search_api_action_id]);
            session(['search_api_status_id' => $request->search_api_status_id]);
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = ApiHistory::query();
        // モールの条件がある場合
        if(session('search_mall_id') != null){
            // 条件を指定して取得
            $query = $query->where('mall_id', session('search_mall_id'));
        }
        // 実行内容の条件がある場合
        if(session('search_api_action_id') != null){
            // 条件を指定して取得
            $query = $query->where('api_action_id', session('search_api_action_id'));
        }
        // モールの条件がある場合
        if(session('search_api_status_id') != null){
            // 条件を指定して取得
            $query = $query->where('api_status_id', session('search_api_status_id'));
        }
        // 並び替えを実施
        return $query->orderBy('updated_at', 'desc');
    }
}