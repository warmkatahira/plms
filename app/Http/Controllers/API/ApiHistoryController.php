<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Mall;
use App\Models\ApiAction;
use App\Models\ApiStatus;
// サービス
use App\Services\API\ApiHistorySearchService;
// トレイト
use App\Traits\PaginatesResultsTrait;

class ApiHistoryController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'API履歴']);
        // インスタンス化
        $ApiHistorySearchService = new ApiHistorySearchService;
        // セッションを削除
        $ApiHistorySearchService->deleteSession();
        // セッションに検索条件を格納
        $ApiHistorySearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $ApiHistorySearchService->getSearchResult();
        // ページネーションを実施
        $api_histories = $this->setPagination($result);
        // モールを取得
        $malls = Mall::getAll()->get();
        // APIアクションを取得
        $api_actions = ApiAction::getAll()->get();
        // APIステータスを取得
        $api_statuses = ApiStatus::getAll()->get();
        return view('api.api_history.index')->with([
            'api_histories' => $api_histories,
            'malls' => $malls,
            'api_actions' => $api_actions,
            'api_statuses' => $api_statuses,
        ]);
    }
}