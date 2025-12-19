<?php

namespace App\Http\Controllers\SystemAdmin\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '会社']);
        // 会社を取得
        $companies = Company::getAll()->with('users')->get();
        return view('system_admin.company.index')->with([
            'companies' => $companies,
        ]);
    }
}