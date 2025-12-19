<?php

namespace App\Http\Controllers\SystemAdmin\Mall;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Mall;

class MallController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'モール']);
        // モールを取得
        $malls = Mall::getAll()->get();
        return view('system_admin.mall.index')->with([
            'malls' => $malls,
        ]);
    }
}