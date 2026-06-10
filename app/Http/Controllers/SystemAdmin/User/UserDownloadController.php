<?php

namespace App\Http\Controllers\SystemAdmin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\SystemAdmin\User\UserSearchService;
use App\Services\SystemAdmin\User\UserDownloadService;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class EmployeeDownloadController extends Controller
{
    public function download()
    {
        // インスタンス化
        $UserSearchService = new UserSearchService;
        $UserDownloadService = new UserDownloadService;
        // 検索結果を取得
        $result = $UserSearchService->getSearchResult();
        // ダウンロードするデータを取得
        $response = $UserDownloadService->getDownloadData($result);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::getSystemTitle().'】ユーザーデータ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
