<?php

namespace App\Http\Controllers\Admin\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Vehicle;
// サービス
use App\Services\Admin\Vehicle\VehicleSearchService;
use App\Services\Admin\Vehicle\VehicleDownloadService;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class VehicleDownloadController extends Controller
{
    public function download()
    {
        // インスタンス化
        $VehicleSearchService = new VehicleSearchService;
        $VehicleDownloadService = new VehicleDownloadService;
        // 検索結果を取得
        $result = $VehicleSearchService->getSearchResult();
        // ダウンロードするデータを取得
        $response = $VehicleDownloadService->getDownloadData($result);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::getSystemTitle().'】車両マスタ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
