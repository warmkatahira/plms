<?php

namespace App\Http\Controllers\Setting\Shipper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Shipper;
// サービス
use App\Services\Setting\Shipper\ShipperCreateService;
// リクエスト
use App\Http\Requests\Setting\Shipper\ShipperCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class ShipperCreateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '荷送人追加']);
        return view('setting.shipper.create')->with([
        ]);
    }

    public function create(ShipperCreateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $ShipperCreateService = new ShipperCreateService;
                // 荷送人を追加
                $ShipperCreateService->createShipper($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '荷送人の追加に失敗しました。',
            ]);
        }
        return redirect()->route('shipper.index')->with([
            'alert_type' => 'success',
            'alert_message' => '荷送人を追加しました。',
        ]);
    }
}