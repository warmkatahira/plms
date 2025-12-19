<?php

namespace App\Services\Shipping\ShippingWorkEnd;

// モデル
use App\Models\ShippingGroup;
use App\Models\Order;
use App\Models\Stock;
use App\Models\ShippingWorkEndHistory;
// サービス
use App\Services\Common\ImportErrorCreateService;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
// 例外
use App\Exceptions\ShippingWorkEndException;

class ShippingWorkEndService
{
    // 出荷完了対象を取得
    public function getShippingWorkEndTarget($shipping_group_id)
    {
        return Order::getShippingWorkEndTarget($shipping_group_id)
                    ->lockForUpdate()
                    ->get();
    }

    // 出荷完了対象が正常に完了処理できるか確認
    public function isShippingWorkEndAvailable($orders)
    {
        // インスタンス化
        $ImportErrorCreateService = new ImportErrorCreateService;
        // 出荷完了対象が存在しない場合
        if($orders->isEmpty()){
            throw new ShippingWorkEndException('出荷完了対象が存在しないため、出荷完了を中断しました。', $orders->count(), 0);
        }
        // 配送伝票番号がNullの受注がある場合
        if($orders->contains('tracking_no', null)){
            // エラー情報を取得
            $errors = $orders->where('tracking_no', null)
                        ->map(function ($order) {
                            return [$order->order_control_id];
                        })
                        ->toArray();
            // エラー情報のファイルを作成
            $error_file_name = $ImportErrorCreateService->createImportError('出荷完了エラー', $errors, CarbonImmutable::now(), ['受注管理ID']);
            throw new ShippingWorkEndException('出荷完了対象に配送伝票番号が未更新の受注が存在するため、出荷完了を中断しました。', $orders->count(), 0, $error_file_name);
        }
        // 受注管理IDを取得
        return $orders->pluck('order_control_id');
    }

    // stocksテーブル更新処理
    public function updateStock($order_control_ids)
    {
        // インスタンス化
        $ImportErrorCreateService = new ImportErrorCreateService;
        // 出荷完了される受注の商品毎の受注数を取得(在庫管理されている商品のみ取得)
        $order_quantities = Order::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                                ->join('order_item_components', 'order_item_components.order_item_id', 'order_items.order_item_id')
                                ->join('bases', 'bases.base_id', 'orders.shipping_base_id')
                                ->whereIn('orders.order_control_id', $order_control_ids)
                                ->where('order_item_components.is_stock_managed_snapshot', true)
                                ->select('order_item_components.allocated_component_item_id as item_id', 'order_item_components.item_code_snapshot as item_code', 'base_name', 'shipping_base_id', DB::raw('SUM(order_item_components.ship_quantity) as total_ship_quantity'))
                                ->groupBy('order_item_components.allocated_component_item_id', 'order_item_components.item_code_snapshot', 'base_name', 'shipping_base_id')
                                ->lockForUpdate()
                                ->get();
        // 更新に必要な情報を格納する配列を初期化
        $stocks = [];
        // エラー情報を格納する配列を初期化
        $errors = [];
        // 検品結果の在庫情報の分だけループ処理
        foreach($order_quantities as $order_quantity){
            // 在庫テーブルから情報を取得
            $stock = Stock::where('base_id', $order_quantity->shipping_base_id)
                        ->where('item_id', $order_quantity->item_id)
                        ->first();
            // 在庫が取得できなかった場合
            if(is_null($stock)){
                // エラー情報を格納
                $errors[] = [
                    $order_quantity->base_name,
                    $order_quantity->item_code,
                    $order_quantity->total_ship_quantity,
                ];
            }
            // 在庫が取得できた場合
            if(!is_null($stock)){
                // 配列に情報を格納(在庫履歴でマイナスにしておく必要があるので、マイナス符号をつけている)
                $stocks[] = [
                                'stock_id'  => $stock->stock_id,
                                'base_name' => $order_quantity->base_name,
                                'item_code' => $order_quantity->item_code,
                                'quantity'  => '-'.$order_quantity->total_ship_quantity,
                            ];
            }
        }
        // エラーがある場合
        if(!empty($errors)){
            // エラー情報のファイルを作成
            $error_file_name = $ImportErrorCreateService->createImportError('出荷完了エラー', $errors, CarbonImmutable::now(), ['出荷倉庫,商品コード,出荷数']);
            throw new ShippingWorkEndException("在庫が取得できない商品があったため、出荷完了を中断しました。", $order_control_ids->count(), 0, $error_file_name);
        }
        // stock_idだけを抜き出し
        $stock_ids = collect($stocks)->pluck('stock_id');
        // 更新対象の在庫レコードを取得する
        $locked_stocks = Stock::whereIn('stock_id', $stock_ids)->lockForUpdate()->get();
        // 更新対象の在庫の分だけループ処理
        foreach($stocks as $stock){
            // 全在庫数を減らす対象を取得
            $locked_stock = $locked_stocks->where('stock_id', $stock['stock_id'])->first();
            // 全在庫数より出荷数の方が大きい場合
            if($locked_stock->total_stock < abs($stock['quantity'])){
                // エラー情報を格納
                $errors[] = [
                    $order_quantity->base_name,
                    $order_quantity->item_code,
                    $order_quantity->total_ship_quantity,
                    $locked_stock->total_stock,
                ];
            }
            // 全在庫数より出荷数の方が小さい場合
            if($locked_stock->total_stock >= abs($stock['quantity'])){
                // 全在庫数から出荷数を引く
                $locked_stock->decrement('total_stock', abs($stock['quantity']));
            }
        }
        // エラーがある場合
        if(!empty($errors)){
            // エラー情報のファイルを作成
            $error_file_name = $ImportErrorCreateService->createImportError('出荷完了エラー', $errors, CarbonImmutable::now(), ['出荷倉庫,商品コード,出荷数,全在庫数']);
            throw new ShippingWorkEndException("在庫数がマイナスになる在庫があるため、出荷完了を中断しました。", $order_control_ids->count(), 0, $error_file_name);
        }
        return $stocks;
    }

    // ordersテーブル更新処理
    public function updateOrder($order_control_ids)
    {
        // 出荷日、受注ステータス、出荷グループIDを更新
        Order::whereIn('order_control_id', $order_control_ids)->update([
            'shipping_date' => CarbonImmutable::now()->toDateString(),
            'order_status_id' => OrderStatusEnum::SHUKKA_ZUMI,
            'shipping_group_id' => Null,
        ]);
    }
    
    // 出荷グループを削除
    public function deleteShippingGroup()
    {
        // 受注が紐付いていない出荷グループがあれば削除
        ShippingGroup::doesntHave('orders')->delete();
    }

    // 出荷完了履歴に追加
    public function createShippingWorkEndHistory($target_count, $is_successful, $error_file_name, $message)
    {
        // レコードを追加
        ShippingWorkEndHistory::create([
            'target_count'      => $target_count,
            'is_successful'     => $is_successful,
            'message'           => $message,
            'error_file_name'   => $error_file_name,
        ]);
    }
}