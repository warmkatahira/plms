<?php

namespace App\Http\Controllers\Admin\BoardingLocation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\User;
use App\Models\BoardingLocationType;
use App\Models\BoardingLocationCategory;
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
        session(['page_header' => '車両追加']);
        // ユーザーを取得
        $users = User::where('role_id', '!=', RoleEnum::PART)->get();
        // 車両区分を取得
        $vehicle_types = BoardingLocationType::ordered()->get();
        // 車両種別を取得
        $vehicle_categories = BoardingLocationCategory::ordered()->get();
        return view('admin.vehicle.create')->with([
            'users' => $users,
            'vehicle_types' => $vehicle_types,
            'vehicle_categories' => $vehicle_categories,
        ]);
    }

    public function create(BoardingLocationCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $BoardingLocationCreateService = new BoardingLocationCreateService;
                // 車両を追加
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
            'alert_message' => '車両を追加しました。',
        ]);
    }
}