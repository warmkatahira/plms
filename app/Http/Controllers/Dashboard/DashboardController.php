<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Dashboard\InfoGetService;
use App\Services\Dashboard\ChartService;
// その他
use Carbon\CarbonImmutable;

class DashboardController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ダッシュボード']);
        return view('dashboard')->with([
        ]);
    }
}