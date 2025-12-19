<?php

namespace App\Http\Controllers\Shipping\AbcAnalysis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderCategory;
// サービス
use App\Services\Shipping\AbcAnalysis\AbcAnalysisService;

class AbcAnalysisController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ABC分析']);
        // インスタンス化
        $AbcAnalysisService = new AbcAnalysisService;
        // セッションを削除
        $AbcAnalysisService->deleteSession();
        // セッションに検索条件を格納
        $AbcAnalysisService->setSearchCondition($request);
        // 検索結果を取得
        $result = $AbcAnalysisService->getSearchResult();
        // 受注区分を取得
        $order_categories = OrderCategory::getAll()->get();
        return view('shipping.abc_analysis.index')->with([
            'items' => $result,
            'order_categories' => $order_categories,
        ]);
    }

    public function ajax_get_chart_data()
    {
        // インスタンス化
        $AbcAnalysisService = new AbcAnalysisService;
        // 検索結果を取得
        $result = $AbcAnalysisService->getSearchResult();
        return response()->json([
            'result' => $result,
        ]);
    }
}