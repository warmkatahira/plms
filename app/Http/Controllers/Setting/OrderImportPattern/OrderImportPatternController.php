<?php

namespace App\Http\Controllers\Setting\OrderImportPattern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderImportPattern;

class OrderImportPatternController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '受注取込パターン']);
        // 受注取込パターンを取得
        $order_import_patterns = OrderImportPattern::getAll()->get();
        return view('setting.order_import_pattern.index')->with([
            'order_import_patterns' => $order_import_patterns,
        ]);
    }
}