<?php

namespace App\Http\Controllers\SystemAdmin\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\SystemAdmin\Company\CompanyCreateService;
// リクエスト
use App\Http\Requests\SystemAdmin\Company\CompanyCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class CompanyCreateController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '会社追加']);
        return view('system_admin.company.create')->with([
        ]);
    }

    public function create(CompanyCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $CompanyCreateService = new CompanyCreateService;
                // 会社を追加
                $CompanyCreateService->createCompany($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('company.index')->with([
            'alert_type' => 'success',
            'alert_message' => '会社を追加しました。',
        ]);
    }
}