<?php

namespace App\Http\Controllers\Admin\BoardingLocation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\User;
// 列挙
use App\Enums\RoleEnum;
// サービス
use App\Services\Admin\BoardingLocation\BoardingLocationCreateService;
// リクエスト
use App\Http\Requests\Admin\BoardingLocation\BoardingLocationCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class BoardingLocationCreateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '乗降場所追加']);
        return view('admin.boarding_location.create')->with([
        ]);
    }

    public function create(BoardingLocationCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $BoardingLocationCreateService = new BoardingLocationCreateService;
                // 乗降場所を追加
                $BoardingLocationCreateService->createBoardingLocation($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '乗降場所を追加しました。',
        ]);
    }
}