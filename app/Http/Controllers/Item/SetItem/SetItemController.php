<?php

namespace App\Http\Controllers\Item\SetItem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\SetItem;
// サービス
use App\Services\Item\SetItem\SetItemSearchService;
// トレイト
use App\Traits\PaginatesResultsTrait;

class SetItemController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'セット商品']);
        // インスタンス化
        $SetItemSearchService = new SetItemSearchService;
        // セッションを削除
        $SetItemSearchService->deleteSession();
        // セッションに検索条件を格納
        $SetItemSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $SetItemSearchService->getSearchResult(SetItem::query());
        // ページネーションを実施
        $set_items = $this->setPagination($result);
        return view('item.set_item.index')->with([
            'set_items' => $set_items,
        ]);
    }
}