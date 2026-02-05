<?php

namespace App\Http\Controllers\Admin\BoardingLocation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Admin\BoardingLocation\BoardingLocationSearchService;
// トレイト
use App\Traits\PaginatesResultsTrait;

class BoardingLocationController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '乗降場所']);
        // インスタンス化
        $BoardingLocationSearchService = new BoardingLocationSearchService;
        // セッションを削除
        $BoardingLocationSearchService->deleteSession();
        // セッションに検索条件を格納
        $BoardingLocationSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $BoardingLocationSearchService->getSearchResult();
        // ページネーションを実施
        $boarding_locations = $this->setPagination($result);
        return view('admin.boarding_location.index')->with([
            'boarding_locations' => $boarding_locations,
        ]);
    }
}