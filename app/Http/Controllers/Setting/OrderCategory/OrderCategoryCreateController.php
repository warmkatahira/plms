<?php

namespace App\Http\Controllers\Setting\OrderCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderCategory;
use App\Models\Mall;
use App\Models\Shipper;
// サービス
use App\Services\Setting\OrderCategory\OrderCategoryCreateService;
// リクエスト
use App\Http\Requests\Setting\OrderCategory\OrderCategoryCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class OrderCategoryCreateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '受注区分追加']);
        // モールを取得
        $malls = Mall::getAll()->get();
        // 荷送人を取得
        $shippers = Shipper::getAll()->get();
        return view('setting.order_category.create')->with([
            'malls' => $malls,
            'shippers' => $shippers,
        ]);
    }

    public function create(OrderCategoryCreateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $OrderCategoryCreateService = new OrderCategoryCreateService;
                // 受注区分を追加
                $OrderCategoryCreateService->createOrderCategory($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '受注区分の追加に失敗しました。',
            ]);
        }
        return redirect()->route('order_category.index')->with([
            'alert_type' => 'success',
            'alert_message' => '受注区分を追加しました。',
        ]);
    }
}