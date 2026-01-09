<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Base;
// サービス
use App\Services\Admin\Employee\EmployeeCreateService;
// リクエスト
use App\Http\Requests\Admin\Employee\EmployeeCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class EmployeeCreateController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '従業員追加']);
        // 営業所を取得
        $bases = Base::getAll()->get();
        return view('admin.employee.create')->with([
            'bases' => $bases,
        ]);
    }

    public function create(EmployeeCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $EmployeeCreateService = new EmployeeCreateService;
                // 従業員を追加
                $employee = $EmployeeCreateService->createEmployee($request);
                // 有給関連テーブルへレコード追加
                $EmployeeCreateService->createPaidLeave($employee);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('employee.index')->with([
            'alert_type' => 'success',
            'alert_message' => '従業員を追加しました。',
        ]);
    }
}
