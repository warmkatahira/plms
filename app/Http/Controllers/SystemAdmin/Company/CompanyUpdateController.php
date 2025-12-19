<?php

namespace App\Http\Controllers\SystemAdmin\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Company;
// サービス
use App\Services\SystemAdmin\Company\CompanyUpdateService;
// リクエスト
use App\Http\Requests\SystemAdmin\Company\CompanyUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class CompanyUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '会社更新']);
        // 会社を取得
        $company = Company::getSpecify($request->company_id)->first();
        return view('system_admin.company.update')->with([
            'company' => $company,
        ]);
    }

    public function update(CompanyUpdateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $CompanyUpdateService = new CompanyUpdateService;
                // 会社を更新
                $CompanyUpdateService->updateCompany($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('company.index')->with([
            'alert_type' => 'success',
            'alert_message' => '会社を更新しました。',
        ]);
    }
}