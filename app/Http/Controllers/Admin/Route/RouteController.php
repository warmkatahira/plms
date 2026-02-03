<?php

namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\RouteType;
// 列挙
use App\Enums\RouteEnum;
// サービス
use App\Services\Admin\Route\RouteSearchService;
// トレイト
use App\Traits\PaginatesResultsTrait;

class RouteController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ルート']);
        // インスタンス化
        $RouteSearchService = new RouteSearchService;
        // セッションを削除
        $RouteSearchService->deleteSession();
        // セッションに検索条件を格納
        $RouteSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $RouteSearchService->getSearchResult();
        // ページネーションを実施
        $routes = $this->setPagination($result);
        // 所要時間を取得
        $routes = $RouteSearchService->getRequiredMinutes($routes);
        // ルート区分を取得
        $route_types = RouteType::ordered()->get();
        return view('admin.route.index')->with([
            'routes' => $routes,
            'route_types' => $route_types,
        ]);
    }
}