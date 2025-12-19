<?php

namespace App\Http\Controllers\Item\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Item;
use App\Models\DeliveryCompany;
// サービス
use App\Services\Item\Item\ItemUpdateService;
use App\Services\Item\Item\ItemDeleteService;
// リクエスト
use App\Http\Requests\Item\Item\ItemUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class ItemUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '単品商品更新']);
        // 商品を取得
        $item = Item::getSpecify($request->item_id)->first();
        // 運送会社を取得
        $delivery_companies = DeliveryCompany::getAll()->with('shipping_methods')->get();
        return view('item.item.update')->with([
            'item' => $item,
            'delivery_companies' => $delivery_companies,
        ]);
    }

    public function update(ItemUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $ItemUpdateService = new ItemUpdateService;
                $ItemDeleteService = new ItemDeleteService;
                // 更新できる商品であるか確認
                $ItemUpdateService->checkUpdatableItem($request);
                // 商品を更新
                $item = $ItemUpdateService->updateItem($request);
                // 商品画像を削除
                $ItemDeleteService->deleteItemImage($item->item_image_file_name, $request);
                // 商品画像を保存
                $ItemUpdateService->saveItemImage($request, $item);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => '商品を更新しました。',
        ]);
    }
}