<?php

namespace App\Http\Controllers\Item\SetItem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\SetItem;
// サービス
use App\Services\Item\SetItem\SetItemSearchService;
use App\Services\Item\SetItem\SetItemDownloadService;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class SetItemDownloadController extends Controller
{
    public function download()
    {
        // インスタンス化
        $SetItemSearchService = new SetItemSearchService;
        $SetItemDownloadService = new SetItemDownloadService;
        // 検索結果を取得
        $result = $SetItemSearchService->getSearchResult(SetItem::query());
        // ダウンロードするデータを取得
        $response = $SetItemDownloadService->getDownloadData($result);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::getSystemTitle().'】セット商品データ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
