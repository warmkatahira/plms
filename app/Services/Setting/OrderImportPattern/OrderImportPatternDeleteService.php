<?php

namespace App\Services\Setting\OrderImportPattern;

// モデル
use App\Models\OrderImportPattern;

class OrderImportPatternDeleteService
{
    // 受注取込パターンを削除
    public function deleteOrderImportPattern($request)
    {
        // 受注取込パターンを削除
        OrderImportPattern::getSpecify($request->order_import_pattern_id)->delete();
    }
}