<?php

namespace App\Services\Shipping\ShippingWorkEndHistory;

// モデル
use App\Models\ShippingWorkEndHistory;
// その他
use Carbon\CarbonImmutable;

class ShippingWorkEndHistorySearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_shipping_work_end_date_from',
            'search_shipping_work_end_date_to',
        ]);
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
            // 当日の日付をセッションに格納
            session(['search_shipping_work_end_date_from' => CarbonImmutable::now()->toDateString()]);
            session(['search_shipping_work_end_date_to' => CarbonImmutable::now()->toDateString()]);
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_shipping_work_end_date_from' => $request->search_shipping_work_end_date_from]);
            session(['search_shipping_work_end_date_to' => $request->search_shipping_work_end_date_to]);
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = ShippingWorkEndHistory::query();
        // 出荷完了日の条件がある場合
        if(!empty(session('search_shipping_work_end_date_from')) && !empty(session('search_shipping_work_end_date_to'))){
            $query = $query->whereDate('created_at', '>=', session('search_shipping_work_end_date_from'))
                            ->whereDate('created_at', '<=', session('search_shipping_work_end_date_to'));
        }
        // 並び替えを実施
        return $query->orderBy('created_at', 'asc');
    }
}