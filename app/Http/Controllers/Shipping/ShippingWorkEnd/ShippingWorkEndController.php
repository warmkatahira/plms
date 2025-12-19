<?php

namespace App\Http\Controllers\Shipping\ShippingWorkEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\ShippingGroup;
// サービス
use App\Services\Shipping\ShippingWorkEnd\ShippingWorkEndService;
use App\Services\Shipping\ShippingWorkEnd\MallShipmentService;
use App\Services\Common\StockHistoryCreateService;
use App\Services\Common\MieruService;
// リクエスト
use App\Http\Requests\Shipping\ShippingWorkEnd\ShippingWorkEndEnterRequest;
// 列挙
use App\Enums\StockHistoryCategoryEnum;
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;
// 例外
use App\Exceptions\ShippingWorkEndException;

class ShippingWorkEndController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '出荷完了']);
        // 出荷グループを取得
        $shipping_groups = ShippingGroup::orderBy('estimated_shipping_date', 'asc')
                                ->orderBy('shipping_base_id', 'asc')
                                ->with('orders')
                                ->with('base')
                                ->withCount([
                                    // 検品完了（true）
                                    'orders as completed_orders_count' => function ($query) {
                                        $query->where('is_shipping_inspection_complete', true);
                                    },
                                    // 未検品（false）
                                    'orders as incomplete_orders_count' => function ($query) {
                                        $query->where('is_shipping_inspection_complete', false);
                                    },
                                ])
                                ->get();
        return view('shipping.shipping_work_end.index')->with([
            'shipping_groups' => $shipping_groups,
        ]);
    }

    public function enter(ShippingWorkEndEnterRequest $request)
    {
        // インスタンス化
        $ShippingWorkEndService = new ShippingWorkEndService;
        $MieruService = new MieruService;
        // ミエルの進捗を更新する対象を取得
        $MieruService->getUpdateProgressTarget(null);
        try {
            DB::transaction(function () use($request, $ShippingWorkEndService) {
                // インスタンス化
                $StockHistoryCreateService = new StockHistoryCreateService;
                $MallShipmentService = new MallShipmentService;
                // 出荷完了対象を取得
                $orders = $ShippingWorkEndService->getShippingWorkEndTarget($request->shipping_group_id);
                // 出荷完了対象が正常に完了処理できるか確認
                $order_control_ids = $ShippingWorkEndService->isShippingWorkEndAvailable($orders);
                // stocksテーブル更新処理
                $stocks = $ShippingWorkEndService->updateStock($order_control_ids);
                // ordersテーブル更新処理
                $ShippingWorkEndService->updateOrder($order_control_ids);
                // 出荷グループを削除
                $ShippingWorkEndService->deleteShippingGroup();
                // 出荷完了履歴に追加
                $ShippingWorkEndService->createShippingWorkEndHistory($order_control_ids->count(), 1, null, null);
                // 在庫履歴に追加
                $StockHistoryCreateService->createStcokHistory(StockHistoryCategoryEnum::SHUKKA, null, $stocks);
                // モール側の出荷処理を実施
                $MallShipmentService->completeMallShipment($order_control_ids);
            });
        } catch (ShippingWorkEndException $e) {
            // 渡された内容を取得
            $target_count = $e->getTargetCount();
            $is_successful = $e->getIsSuccessful();
            $error_file_name = $e->getErrorFileName();
            // 出荷完了履歴に追加
            $ShippingWorkEndService->createShippingWorkEndHistory($target_count, $is_successful, $error_file_name, $e->getMessage());
            return redirect()->route('shipping_work_end_history.index')->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        // 出荷確定をミエルに送信
        $MieruService->updateShippingConfirmed();
        return redirect()->route('shipping_work_end_history.index')->with([
            'alert_type' => 'success',
            'alert_message' => '出荷完了が完了しました。',
        ]);
    }
}