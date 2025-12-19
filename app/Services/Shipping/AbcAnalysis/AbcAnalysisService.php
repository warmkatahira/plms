<?php

namespace App\Services\Shipping\AbcAnalysis;

// モデル
use App\Models\Order;
// 列挙
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class AbcAnalysisService
{
    // セッションを削除
    public function deleteSession()
    {
        session()->forget([
            'search_shipping_date_from',
            'search_shipping_date_to',
            'search_order_category_id',
        ]);
    }

    // セッションに検索条件を格納
    public function setSearchCondition($request)
    {
        // 現在のURLを取得
        session(['back_url_1' => url()->full()]);
        // パラメータが存在するかしないかで可変
        if($request->has('disp_type')){
            session(['disp_type' => $request->disp_type]);
        }else{
            session(['disp_type' => 'list']);
        }
        // 変数が存在しない場合は検索が実行されていないので、初期条件をセット
        if(!isset($request->search_type)){
            // 当日の日付をセッションに格納
            session(['search_shipping_date_from' => CarbonImmutable::now()->startOfMonth()->toDateString()]);
            session(['search_shipping_date_to' => CarbonImmutable::now()->endOfMonth()->toDateString()]);
        }
        // 「search」なら検索が実行されているので、検索条件をセット
        if($request->search_type === 'search'){
            session(['search_shipping_date_from' => $request->search_shipping_date_from]);
            session(['search_shipping_date_to' => $request->search_shipping_date_to]);
            session(['search_order_category_id' => $request->search_order_category_id]);
        }
    }

    // 検索結果を取得
    public function getSearchResult()
    {
        // 注文ステータスを指定して受注データを取得
        $query = Order::where('order_status_id', OrderStatusEnum::SHUKKA_ZUMI)
                    ->join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                    ->join('order_item_components', 'order_item_components.order_item_id', 'order_items.order_item_id')
                    ->select('order_item_components.allocated_component_item_id', 'order_item_components.item_jan_code_snapshot as item_jan_code', 'order_item_components.item_name_snapshot as item_name', DB::raw('SUM(ship_quantity) as total_ship_quantity'))
                    ->groupBy('order_item_components.allocated_component_item_id', 'order_item_components.item_jan_code_snapshot', 'order_item_components.item_name_snapshot')
                    ->orderBy('total_ship_quantity', 'desc')
                    ->orderBy('order_item_components.allocated_component_item_id', 'asc');
        // 出荷日の条件がある場合
        if (!empty(session('search_shipping_date_from')) && !empty(session('search_shipping_date_to'))) {
            $query = $query->whereDate('shipping_date', '>=', session('search_shipping_date_from'))
                            ->whereDate('shipping_date', '<=', session('search_shipping_date_to'));
        }
        // 受注区分の条件がある場合
        if(session('search_order_category_id') != null){
            // 条件を指定して取得
            $query = $query->where('order_category_id', session('search_order_category_id'));
        }
        // 取得
        $query = $query->get();
        // 全体の合計数量を取得
        $grand_total = $query->sum('total_ship_quantity');
        // 商品の分だけループ処理
        foreach($query as $item){
            // 各商品の構成比（%）を計算して結果に返す
            $item->ratio = $item->total_ship_quantity / $grand_total * 100;
        }
        // 累積構成比を格納する変数を初期化
        $cumulative = 0;
        // 商品の分だけループ処理
        foreach($query as $item) {
            // 累積構成比を加算
            $cumulative += $item->ratio;
            // 累積構成比を結果に返す
            $item->cumulative_ratio = $cumulative;
        }
        // 商品の分だけループ処理
        foreach($query as $item) {
            // 累積構成比が70%以下の場合
            if($item->cumulative_ratio <= 70){
                $item->rank = 'A';
            // 累積構成比が90%以下の場合
            }elseif($item->cumulative_ratio <= 90){
                $item->rank = 'B';
            // 上記以外の場合
            }else{
                $item->rank = 'C';
            }
        }
        return $query;
    }
}