<?php

namespace App\Services\Order\OrderAllocate\ItemAllocate;

// モデル
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemComponent;
// サービス
use App\Services\Order\OrderAllocate\ItemAllocate\ItemAllocateAutoProcessCreateItemService;
use App\Services\Order\OrderAllocate\ItemAllocate\ItemAllocateMakeshopService;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;

class ItemAllocateService
{
    // 商品引当処理
    public function allocateItem($allocate_orders)
    {
        // インスタンス化
        $ItemAllocateAutoProcessCreateItemService = new ItemAllocateAutoProcessCreateItemService;
        $ItemAllocateMakeshopService = new ItemAllocateMakeshopService;
        // 自動処理で追加された商品の商品引当
        $ItemAllocateAutoProcessCreateItemService->process($allocate_orders);
        // メイクショップからの注文の商品引当
        $ItemAllocateMakeshopService->process($allocate_orders);
        // 商品引当NG対象を取得
        $item_allocate_ng_orders = OrderItem::whereIn('order_control_id', $allocate_orders)
                                    ->where('is_item_allocated', false)
                                    ->distinct()
                                    ->pluck('order_control_id');
        // 商品引当がNGの受注はここで注文ステータスを「確認待ち」に更新する
        Order::whereIn('order_control_id', $item_allocate_ng_orders)->update([
            'order_status_id' => OrderStatusEnum::KAKUNIN_MACHI,
        ]);
        // 受注商品のスナップショット関連カラムが空欄のレコードを商品マスタの値で更新
        OrderItem::whereNull('item_name_snapshot')
            ->update([
                'item_name_snapshot' => DB::raw("
                    CASE 
                        WHEN allocated_item_id IS NOT NULL 
                            THEN (SELECT item_name FROM items WHERE items.item_id = order_items.allocated_item_id)
                        WHEN allocated_set_item_id IS NOT NULL 
                            THEN (SELECT set_item_name FROM set_items WHERE set_items.set_item_id = order_items.allocated_set_item_id)
                        ELSE NULL
                    END
                ")
            ]);
        // 受注商品構成のスナップショット関連カラムが空欄のレコードを商品マスタの値で更新
        OrderItemComponent::join('items', 'items.item_id', 'order_item_components.allocated_component_item_id')
            ->whereNull('order_item_components.item_code_snapshot')
            ->update([
                'order_item_components.item_code_snapshot' => DB::raw('items.item_code'),
                'order_item_components.item_jan_code_snapshot' => DB::raw('items.item_jan_code'),
                'order_item_components.item_name_snapshot' => DB::raw('items.item_name'),
                'order_item_components.is_stock_managed_snapshot' => DB::raw('items.is_stock_managed'),
                'order_item_components.is_shipping_inspection_required_snapshot' => DB::raw('items.is_shipping_inspection_required'),
                'order_item_components.is_hide_on_delivery_note_snapshot' => DB::raw('items.is_hide_on_delivery_note'),
            ]);
    }
}