<?php

namespace App\Http\Controllers\SystemAdmin\RouteType;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\RouteType;
// トレイト
use App\Traits\PaginatesResultsTrait;

class RouteTypeController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ルート区分']);
        // ルート区分を取得
        $route_types = RouteType::ordered();
        // ページネーションを実施
        $route_types = $this->setPagination($route_types);
        return view('system_admin.route_type.index')->with([
            'route_types' => $route_types,
        ]);
    }
}