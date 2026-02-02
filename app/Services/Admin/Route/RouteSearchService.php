<?php

namespace App\Services\Admin\Route;

// モデル
use App\Models\Route;
// その他
use Illuminate\Support\Facades\DB;

class RouteSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_is_active',
            'search_route_type_id',
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
            session(['search_route_type_id' => $request->search_route_type_id]);
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = Route::with(['route_details.boarding_location', 'vehicle_category', 'route_type']);
        // 利用可否の条件がある場合
        if(session('search_is_active') != null){
            // 条件を指定して取得
            $query = $query->where('is_active', session('search_is_active'));
        }
        // ルート区分の条件がある場合
        if(session('search_route_type_id') != null){
            // 条件を指定して取得
            $query = $query->where('route_type_id', session('search_route_type_id'));
        }
        // 並び替えを実施
        return $query->orderBy('sort_order', 'asc');
    }
}