<?php

namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Route;
// サービス
use App\Services\Admin\Route\RouteSearchService;
use App\Services\Admin\Route\RouteDownloadService;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class RouteDownloadController extends Controller
{
    public function download()
    {
        // インスタンス化
        $RouteSearchService = new RouteSearchService;
        $RouteDownloadService = new RouteDownloadService;
        // 検索結果を取得
        $result = $RouteSearchService->getSearchResult();
        $result = $RouteSearchService->getRequiredMinutes($result->get());
        // ダウンロードするデータを取得
        $response = $RouteDownloadService->getDownloadData($result);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::getSystemTitle().'】ルートマスタ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}