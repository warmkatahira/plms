<?php

namespace App\Http\Controllers\Order\OrderImport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderImportPattern;
// サービス
use App\Services\Order\OrderImport\OrderImportHistorySearchService;
// トレイト
use App\Traits\PaginatesResultsTrait;

class OrderImportController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '受注取込']);
        // インスタンス化
        $OrderImportHistorySearchService = new OrderImportHistorySearchService;
        // セッションを削除
        $OrderImportHistorySearchService->deleteSession();
        // セッションに検索条件を格納
        $OrderImportHistorySearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $OrderImportHistorySearchService->getSearchResult();
        // ページネーションを実施
        $order_import_histories = $this->setPagination($result);
        // 受注取込パターンを取得
        $order_import_patterns = OrderImportPattern::getAll()->get();
        return view('order.order_import.index')->with([
            'order_import_histories' => $order_import_histories,
            'order_import_patterns' => $order_import_patterns,
        ]);
    }
}