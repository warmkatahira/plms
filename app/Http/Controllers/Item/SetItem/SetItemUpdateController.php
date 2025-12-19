<?php

namespace App\Http\Controllers\Item\SetItem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\SetItem;
use App\Models\DeliveryCompany;
// サービス
use App\Services\Item\SetItem\SetItemUpdateService;
// リクエスト
use App\Http\Requests\Item\SetItem\SetItemUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class SetItemUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'セット商品更新']);
        // セット商品を取得
        $set_item = SetItem::getSpecify($request->set_item_id)->first();
        // 運送会社を取得
        $delivery_companies = DeliveryCompany::getAll()->with('shipping_methods')->get();
        return view('item.set_item.update')->with([
            'set_item' => $set_item,
            'delivery_companies' => $delivery_companies,
        ]);
    }

    public function update(SetItemUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $SetItemUpdateService = new SetItemUpdateService;
                // セット商品を更新
                $set_item = $SetItemUpdateService->updateSetItem($request);
                // セット商品画像を削除
                $SetItemUpdateService->deleteSetItemImage($request, $set_item);
                // セット商品画像を保存
                $SetItemUpdateService->saveSetItemImage($request, $set_item);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => 'セット商品を更新しました。',
        ]);
    }
}