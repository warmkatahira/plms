<?php

namespace App\Services\SystemAdmin\Company;

// モデル
use App\Models\Company;

class CompanyUpdateService
{
    // 会社を更新
    public function updateCompany($request)
    {
        // 会社を取得
        $company = Company::getSpecify($request->company_id)->lockForUpdate()->first();
        // 会社を更新
        $company->update([
            'company_name'          => $request->company_name,
            'sort_order'            => $request->sort_order,
        ]);
    }
}