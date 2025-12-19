<?php

namespace App\Services\Order\OrderImport;

// モデル
use App\Models\OrderImportHistory;
// その他
use Carbon\CarbonImmutable;

class OrderImportHistorySearchService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_import_date_from',
            'search_import_date_to',
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
            session(['search_import_date_from' => CarbonImmutable::now()->toDateString()]);
            session(['search_import_date_to' => CarbonImmutable::now()->toDateString()]);
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_import_date_from' => $request->search_import_date_from]);
            session(['search_import_date_to' => $request->search_import_date_to]);
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // クエリをセット
        $query = OrderImportHistory::query();
        // 取込日の条件がある場合
        if(!empty(session('search_import_date_from')) && !empty(session('search_import_date_to'))){
            $query = $query->whereDate('created_at', '>=', session('search_import_date_from'))
                            ->whereDate('created_at', '<=', session('search_import_date_to'));
        }
        // 並び替えを実施
        return $query->orderBy('created_at', 'asc');
    }
}