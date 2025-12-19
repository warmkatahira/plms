<?php

namespace App\Services\Order\OrderAllocate;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemComponent;
// サービス
use App\Services\Common\MieruService;
use App\Services\Order\OrderAllocate\ItemAllocate\ItemAllocateService;
use App\Services\Order\OrderAllocate\StockAllocateService;
use App\Services\Order\OrderAllocate\OrderAllocateService;
// その他
use Illuminate\Support\Facades\DB;

class AllocateService
{
    // 引当処理
    public function allocateItemAndStockAndOrder($order_control_id)
    {
        try {
            DB::transaction(function () use ($order_control_id){
                // インスタンス化
                $ItemAllocateService = new ItemAllocateService;
                $StockAllocateService = new StockAllocateService;
                $OrderAllocateService = new OrderAllocateService;
                // 引当対象を取得
                $allocate_orders = $this->getAllocateOrder($order_control_id);
                // 引当対象がない場合
                if($allocate_orders->isEmpty()){
                    // 処理終了
                    return;
                }
                // 商品引当処理
                $ItemAllocateService->allocateItem($allocate_orders);
                // 在庫引当処理
                $StockAllocateService->allocateStock($allocate_orders);
                // 引当済み処理
                $OrderAllocateService->allocateOrder($allocate_orders);
            });
        } catch (\Exception $e){
            return with([
                'type' => 'error',
                'message' => '引当処理に失敗しました。',
            ]);
        }
        // インスタンス化
        $MieruService = new MieruService;
        // ミエルの進捗を更新する対象を取得
        $MieruService->getUpdateProgressTarget(null);
        return with([
            'type' => 'success',
            'message' => '引当処理が完了しました。',
        ]);
    }

    // 引当対象を取得
    public function getAllocateOrder($order_control_id)
    {
        // 受注管理IDのパラメータがNullの場合(指定がない場合)
        if(is_null($order_control_id)){
            // 引当済みが「0」かつ出荷倉庫IDがNull以外の受注を取得（ここでロック）
            $allocate_orders = Order::where('is_allocated', false)
                                ->whereNotNull('shipping_base_id')
                                ->select('order_control_id')
                                ->lockForUpdate()
                                ->get();
        }
        // 受注管理IDのパラメータがNull以外の場合(指定がある場合)
        if(!is_null($order_control_id)){
            // 引当済みが「0」かつ出荷倉庫IDがNull以外の受注を取得（ここでロック）
            $allocate_orders = Order::where('order_control_id', $order_control_id)
                                ->where('is_allocated', false)
                                ->whereNotNull('shipping_base_id')
                                ->select('order_control_id')
                                ->lockForUpdate()
                                ->get();
        }
        // 受注管理IDを取得
        $order_control_ids = $allocate_orders->pluck('order_control_id');
        // ordersに関連するorder_itemsとorder_item_componentsをロック
        $order_items = OrderItem::whereIn('order_control_id', $order_control_ids)->lockForUpdate()->get();
        OrderItemComponent::whereIn('order_item_id', $order_items->pluck('order_item_id'))->lockForUpdate()->get();
        return $allocate_orders;
    }
}