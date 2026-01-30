<?php

namespace App\Http\Controllers\Admin\BoardingLocation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\BoardingLocation;
// 列挙
use App\Enums\RoleEnum;
// サービス
use App\Services\Admin\BoardingLocation\BoardingLocationUpdateService;
// リクエスト
use App\Http\Requests\Admin\BoardingLocation\BoardingLocationUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class BoardingLocationUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '乗降場所更新']);
        // 乗降場所を取得
        $boarding_location = BoardingLocation::byPk($request->boarding_location_id)->first();
        return view('admin.boarding_location.update')->with([
            'boarding_location' => $boarding_location,
        ]);
    }

    public function update(BoardingLocationUpdateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $BoardingLocationUpdateService = new BoardingLocationUpdateService;
                // 乗降場所を更新
                $BoardingLocationUpdateService->updateBoardingLocation($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '乗降場所を更新しました。',
        ]);
    }
}