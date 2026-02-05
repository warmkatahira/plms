<?php

namespace App\Services\Ride\RideSchedule;

// モデル
use App\Models\Ride;
// その他
use Illuminate\Support\Facades\DB;

class RideScheduleSearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_is_active',
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
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = Ride::with(['route_type', 'user', 'vehicle']);
        // 運行状況の条件がある場合
        if(session('search_is_active') != null){
            // 条件を指定して取得
            $query = $query->where('is_active', session('search_is_active'));
        }
        // 並び替えを実施
        return $query->orderBy('schedule_date', 'asc')->orderBy('ride_id', 'asc');
    }
}