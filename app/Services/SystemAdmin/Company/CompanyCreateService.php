<?php

namespace App\Services\SystemAdmin\Company;

// モデル
use App\Models\Company;

class CompanyCreateService
{
    // 会社を追加
    public function createCompany($request)
    {
        // 会社を追加
        Company::create([
            'company_id'            => $request->company_id,
            'company_name'          => $request->company_name,
            'sort_order'            => $request->sort_order,
        ]);
    }
}