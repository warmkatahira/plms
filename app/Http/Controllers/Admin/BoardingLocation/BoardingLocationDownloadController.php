<?php

namespace App\Http\Controllers\Admin\BoardingLocation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\BoardingLocation;
// サービス
use App\Services\Admin\BoardingLocation\BoardingLocationSearchService;
use App\Services\Admin\BoardingLocation\BoardingLocationDownloadService;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class BoardingLocationDownloadController extends Controller
{
    public function download()
    {
        // インスタンス化
        $BoardingLocationSearchService = new BoardingLocationSearchService;
        $BoardingLocationDownloadService = new BoardingLocationDownloadService;
        // 検索結果を取得
        $result = $BoardingLocationSearchService->getSearchResult();
        // ダウンロードするデータを取得
        $response = $BoardingLocationDownloadService->getDownloadData($result);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::getSystemTitle().'】乗降場所マスタ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
