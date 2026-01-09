<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\User;
use App\Models\Base;
// サービス
use App\Services\Admin\Employee\EmployeeSearchService;
// トレイト
use App\Traits\PaginatesResultsTrait;

class EmployeeController extends Controller
{
    use PaginatesResultsTrait;

    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '従業員一覧']);
        // インスタンス化
        $EmployeeSearchService = new EmployeeSearchService;
        // セッションを削除
        $EmployeeSearchService->deleteSession();
        // セッションに検索条件を格納
        $EmployeeSearchService->setSearchCondition($request);
        // 検索結果を取得
        $result = $EmployeeSearchService->getSearchResult();
        // ページネーションを実施
        $employees = $this->setPagination($result);
        // 営業所を取得
        $bases = Base::getAll()->get();
        return view('admin.employee.index')->with([
            'employees' => $employees,
            'bases' => $bases,
        ]);
    }
}