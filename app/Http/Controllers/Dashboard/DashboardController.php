<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\User;
// その他
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'ダッシュボード']);
        // 自身の情報を取得
        $employee = User::getSpecify(Auth::user()->user_no)->with(['base', 'paid_leave', 'statutory_leave'])->first();
        return view('dashboard')->with([
            'employee' => $employee,
        ]);
    }
}