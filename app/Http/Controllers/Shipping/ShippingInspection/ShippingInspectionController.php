<?php

namespace App\Http\Controllers\Shipping\ShippingInspection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\ShippingInspection\OrderControlIdCheckService;
use App\Services\Shipping\ShippingInspection\TrackingNoCheckService;
use App\Services\Shipping\ShippingInspection\ItemIdCodeCheckService;
use App\Services\Shipping\ShippingInspection\CompleteService;
use App\Services\Common\MieruService;
// その他
use Illuminate\Support\Facades\DB;

class ShippingInspectionController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '出荷検品']);
        return view('shipping.shipping_inspection.index')->with([
        ]);
    }

    // 受注管理IDが変更された際の処理
    public function ajax_check_order_control_id(Request $request)
    {
        // インスタンス化
        $OrderControlIdCheckService = new OrderControlIdCheckService;
        // 出荷検品できる受注であるか確認
        $error_message = $OrderControlIdCheckService->check($request->order_control_id);
        // 結果を返す
        return response()->json([
            'error_message' => $error_message,
        ]);
    }

    // 配送伝票番号が変更された際の処理
    public function ajax_check_tracking_no(Request $request)
    {
        // インスタンス化
        $TrackingNoCheckService = new TrackingNoCheckService;
        // 配送伝票番号が一致しているか確認
        $error_message = $TrackingNoCheckService->check($request);
        // エラーが無かった場合のみ実施
        if(!$error_message){
            // 検品する商品情報を取得
            $inspection_targets = $TrackingNoCheckService->getInspectionTarget($request->order_control_id);
            // 注文全体の検品数量を取得
            $total_ship_quantity = $inspection_targets->sum('total_ship_quantity');
            // セッションに商品情報を格納
            $TrackingNoCheckService->setInspectionTarget($inspection_targets);
        }
        // 結果を返す
        return response()->json([
            'error_message' => $error_message,
            'inspection_targets' => $error_message ? null : $inspection_targets,
            'total_ship_quantity' => $total_ship_quantity,
        ]);
    }

    // 商品識別コードが変更された際の処理
    public function ajax_check_item_id_code(Request $request)
    {
        // インスタンス化
        $ItemIdCodeCheckService = new ItemIdCodeCheckService;
        // 検品対象の商品か確認し、問題なければ検品数をカウントアップ
        $ItemIdCodeCheckService->check($request);
        // 結果を返す
        return response()->json([
            'error_info' => session('error_info'),
            'item_id' => session('item_id'),
            'inspection_quantity' => session('inspection_quantity'),
            'inspection_complete' => session('inspection_complete'),
            'inspection_complete_order' => session('inspection_complete_order'),
            'inspection' => session('inspection'),
        ]);
    }

    public function complete(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $CompleteService = new CompleteService;
                // ordersテーブルを更新
                $CompleteService->updateInspectionResultForOrder($request->order_control_id);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        // インスタンス化
        $MieruService = new MieruService;
        // ミエルの進捗を更新する対象を取得
        $MieruService->getUpdateProgressTarget($request->order_control_id);
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '検品が完了しました。',
        ]);
    }
}