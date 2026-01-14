<?php

namespace App\Http\Controllers\Admin\EmployeeImportHistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Admin\EmployeeImportHistory\EmployeeImportHistorySearchService;
// トレイト
use App\Traits\PaginatesResultsTrait;

class EmployeeImportHistoryController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '従業員取込履歴']);
        // インスタンス化
        $EmployeeImportHistorySearchService = new EmployeeImportHistorySearchService;
        // セッションを削除
        $EmployeeImportHistorySearchService->deleteSession();
        // セッションに検索条件を格納
        $EmployeeImportHistorySearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $EmployeeImportHistorySearchService->getSearchResult();
        // ページネーションを実施
        $employee_import_histories = $this->setPagination($result);
        return view('admin.employee_import_history.index')->with([
            'employee_import_histories' => $employee_import_histories,
        ]);
    }
}