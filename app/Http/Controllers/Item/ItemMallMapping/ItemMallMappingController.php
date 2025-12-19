<?php

namespace App\Http\Controllers\Item\ItemMallMapping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Mall;
use App\Models\Item;
// サービス
use App\Services\Item\ItemMallMapping\ItemMallMappingSearchService;
use App\Services\Item\Item\ItemSearchService;
use App\Services\Item\SetItem\SetItemSearchService;
// トレイト
use App\Traits\PaginatesResultsTrait;

class ItemMallMappingController extends Controller
{
    use PaginatesResultsTrait;

    public function index_item(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'モール×単品商品マッピング']);
        // インスタンス化
        $ItemMallMappingSearchService = new ItemMallMappingSearchService;
        $ItemSearchService = new ItemSearchService;
        // セッションを削除
        $ItemSearchService->deleteSession();
        // セッションに検索条件を格納
        $ItemSearchService->setSearchCondition($request);
        // 検索に使用するクエリを取得
        $query = $ItemMallMappingSearchService->getSearchQueryItem();
        // 検索結果を取得
        $result = $ItemSearchService->getSearchResult($query);
        // ページネーションを実施
        $items = $this->setPagination($result);
        // モールを取得
        $malls = Mall::getAll()->get();
        return view('item.item_mall_mapping.index_item')->with([
            'items' => $items,
            'malls' => $malls,
        ]);
    }

    public function index_set_item(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'モール×セット商品マッピング']);
        // インスタンス化
        $ItemMallMappingSearchService = new ItemMallMappingSearchService;
        $SetItemSearchService = new SetItemSearchService;
        // セッションを削除
        $SetItemSearchService->deleteSession();
        // セッションに検索条件を格納
        $SetItemSearchService->setSearchCondition($request);
        // 検索に使用するクエリを取得
        $query = $ItemMallMappingSearchService->getSearchQuerySetItem();
        // 検索結果を取得
        $result = $SetItemSearchService->getSearchResult($query);
        // ページネーションを実施
        $set_items = $this->setPagination($result);
        // モールを取得
        $malls = Mall::getAll()->get();
        return view('item.item_mall_mapping.index_set_item')->with([
            'set_items' => $set_items,
            'malls' => $malls,
        ]);
    }
}