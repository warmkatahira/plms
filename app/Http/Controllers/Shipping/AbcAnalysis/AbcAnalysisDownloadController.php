<?php

namespace App\Http\Controllers\Shipping\AbcAnalysis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\AbcAnalysis\AbcAnalysisService;
use App\Services\Shipping\AbcAnalysis\AbcAnalysisDownloadService;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class AbcAnalysisDownloadController extends Controller
{
    public function download()
    {
        // インスタンス化
        $AbcAnalysisService = new AbcAnalysisService;
        $AbcAnalysisDownloadService = new AbcAnalysisDownloadService;
        // 検索結果を取得
        $result = $AbcAnalysisService->getSearchResult();
        // ダウンロードするデータを取得
        $response = $AbcAnalysisDownloadService->getDownloadData($result);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::getSystemTitle().'】ABB分析データ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
