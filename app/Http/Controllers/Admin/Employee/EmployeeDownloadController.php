<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\SystemAdmin\User\UserSearchService;
use App\Services\Admin\Employee\EmployeeDownloadService;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Gate;
// 列挙
use App\Enums\SystemEnum;

class EmployeeDownloadController extends Controller
{
    public function download()
    {
        // インスタンス化
        $UserSearchService = new UserSearchService;
        $EmployeeDownloadService = new EmployeeDownloadService;
        // 管理者判定を渡す
        $is_admin = Gate::allows('admin_check');
        // 検索結果を取得
        $result = $UserSearchService->getSearchResult();
        // ダウンロードするデータを取得
        $response = $EmployeeDownloadService->getDownloadData($result, $is_admin);
        // ダウンロード処理
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=【'.SystemEnum::getSystemTitle().'】従業員データ_' . CarbonImmutable::now()->isoFormat('Y年MM月DD日HH時mm分ss秒') . '.csv');
        return $response;
    }
}
