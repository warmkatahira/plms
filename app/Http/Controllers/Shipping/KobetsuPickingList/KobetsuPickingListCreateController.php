<?php

namespace App\Http\Controllers\Shipping\KobetsuPickingList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\OrderDocument\OrderDocumentService;

class KobetsuPickingListCreateController extends Controller
{
    public function create(Request $request)
    {
        try{
            // インスタンス化
            $OrderDocumentService = new OrderDocumentService;
            // 出力内容を取得
            $orders = $OrderDocumentService->getIssueOrder($request->shipping_method_id, $request->start, $request->end);
            // 商品ロケーションを取得
            $item_locations = $OrderDocumentService->getItemLocation($orders->first());
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return view('shipping.document.kobetsu_picking_list')->with([
            'orders' => $orders,
            'item_locations' => $item_locations,
        ]);
    }

    public function create_by_select_order(Request $request)
    {
        try{
            // インスタンス化
            $OrderDocumentService = new OrderDocumentService;
            // 作成可能か確認
            $OrderDocumentService->checkCreatable($request->chk);
            // 出力内容を取得
            $orders = $OrderDocumentService->getIssueOrderBySelectOrder($request->chk);
            // 商品ロケーションを取得
            $item_locations = $OrderDocumentService->getItemLocation($orders->first());
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return view('shipping.document.kobetsu_picking_list')->with([
            'orders' => $orders,
            'item_locations' => $item_locations,
        ]);
    }
}