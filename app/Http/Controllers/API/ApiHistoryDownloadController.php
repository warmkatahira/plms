<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\ApiHistory;
// サービス
use App\Services\API\ApiHistorySearchService;
use App\Services\API\ApiHistoryDownloadService;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class ApiHistoryDownloadController extends Controller
{
    public function download()
    {
        // インスタンス化
        $ApiHistorySearchService = new ApiHistorySearchService;
        $ApiHistoryDownloadService = new ApiHistoryDownloadService;
        // 検索結果を取得
        $result = $ApiHistorySearchService->getSearchResult();
        // ダウンロードするデータを取得
        $response = $ApiHistoryDownloadService->getDownloadData($result);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::getSystemTitle().'】API履歴データ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
