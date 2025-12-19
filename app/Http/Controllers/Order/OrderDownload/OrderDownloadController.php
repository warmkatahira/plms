<?php

namespace App\Http\Controllers\Order\OrderDownload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Order\OrderMgt\OrderSearchService;
use App\Services\Order\OrderDownload\OrderDownloadService;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class OrderDownloadController extends Controller
{
    public function download()
    {
        // インスタンス化
        $OrderSearchService = new OrderSearchService;
        $OrderDownloadService = new OrderDownloadService;
        // 検索結果を取得
        $result = $OrderSearchService->getSearchResult();
        // ダウンロードするデータを取得
        $response = $OrderDownloadService->getDownloadData($result);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::getSystemTitle().'】受注データ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}