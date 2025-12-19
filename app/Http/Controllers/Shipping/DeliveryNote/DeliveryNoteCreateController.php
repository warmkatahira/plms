<?php

namespace App\Http\Controllers\Shipping\DeliveryNote;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\OrderDocument\OrderDocumentService;

class DeliveryNoteCreateController extends Controller
{
    public function create(Request $request)
    {
        try{
            // インスタンス化
            $OrderDocumentService = new OrderDocumentService;
            // 出力内容を取得
            $orders = $OrderDocumentService->getIssueOrder($request->shipping_method_id, $request->start, $request->end);
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return view('shipping.document.delivery_note')->with([
            'orders' => $orders,
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
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return view('shipping.document.delivery_note')->with([
            'orders' => $orders,
        ]);
    }
}