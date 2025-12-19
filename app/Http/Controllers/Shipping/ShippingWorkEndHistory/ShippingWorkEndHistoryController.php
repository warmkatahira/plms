<?php

namespace App\Http\Controllers\Shipping\ShippingWorkEndHistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\ShippingWorkEndHistory\ShippingWorkEndHistorySearchService;
// トレイト
use App\Traits\PaginatesResultsTrait;

class ShippingWorkEndHistoryController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '出荷完了履歴']);
        // インスタンス化
        $ShippingWorkEndHistorySearchService = new ShippingWorkEndHistorySearchService;
        // セッションを削除
        $ShippingWorkEndHistorySearchService->deleteSession();
        // セッションに検索条件を格納
        $ShippingWorkEndHistorySearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $ShippingWorkEndHistorySearchService->getSearchResult();
        // ページネーションを実施
        $shipping_work_end_histories = $this->setPagination($result);
        return view('shipping.shipping_work_end_history.index')->with([
            'shipping_work_end_histories' => $shipping_work_end_histories,
        ]);
    }
}
