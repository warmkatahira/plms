<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 列挙
use App\Enums\OrderStatusEnum;
use App\Enums\RouteNameEnum;
use App\Enums\DeliveryTimeZoneEnum;
// その他
use Illuminate\Support\Facades\Route;
use Carbon\CarbonImmutable;

class Order extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'order_control_id',
        'order_import_date',
        'order_import_time',
        'order_status_id',
        'shipping_method_id',
        'shipping_base_id',
        'desired_delivery_date',
        'desired_delivery_time',
        'is_allocated',
        'is_shipping_inspection_complete',
        'shipping_inspection_date',
        'tracking_no',
        'shipping_date',
        'shipping_group_id',
        'order_no',
        'order_date',
        'order_time',
        'buyer_name',
        'ship_name',
        'ship_postal_code',
        'ship_province_name',
        'ship_address',
        'ship_tel',
        'ship_id',
        'shipping_fee',
        'payment_amount',
        'order_message',
        'order_memo',
        'shipping_work_memo',
        'order_category_id',
        'mall_shipping_method',
        'order_mark',
        'is_shipping_method_changed',
    ];
    // 指定したレコードを取得
    public static function getSpecifyByOrderControlId($order_control_id)
    {
        return self::where('orders.order_control_id', $order_control_id);
    }
    // order_itemsテーブルとのリレーション
    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'order_control_id', 'order_control_id')
                    ->orderBy('order_items.order_item_id', 'asc');
    }
    // basesテーブルとのリレーション
    public function base()
    {
        return $this->belongsTo(Base::class, 'shipping_base_id', 'base_id');
    }
    // shipping_methodsテーブルとのリレーション
    public function shipping_method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id', 'shipping_method_id');
    }
    // order_categoriesテーブルとのリレーション
    public function order_category()
    {
        return $this->belongsTo(OrderCategory::class, 'order_category_id', 'order_category_id');
    }
    // shipping_groupsテーブルとのリレーション
    public function shipping_group()
    {
        return $this->belongsTo(ShippingGroup::class, 'shipping_group_id', 'shipping_group_id');
    }
    // 運送会社と配送方法を返すアクセサ
    public function getDeliveryCompanyAndShippingMethodAttribute(): string
    {
        return $this->shipping_method->delivery_company->delivery_company . ' ' . $this->shipping_method->shipping_method;
    }
    // 配送方法変更の文字列を返すアクセサ
    public function getIsShippingMethodChangedTextAttribute(): string
    {
        return $this->is_shipping_method_changed ? '変更済' : '未変更';
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            '注文ステータス',
            '出荷日',
            '取込日',
            '取込時間',
            '注文番号',
            '注文日',
            '注文時間',
            '受注管理ID',
            '受注マーク',
            'モール',
            '受注区分',
            '出荷倉庫',
            '配送先名',
            '運送会社',
            '配送方法',
            '配送希望日',
            '配送希望時間',
            '配送伝票番号',
            'セット商品',
            '(親)商品コード',
            '(親)商品名',
            '(親)注文数',
            '(子)商品コード',
            '(子)商品JANコード',
            '(子)商品名',
            '(子)出荷数',
        ];
    }
    // 指定した注文ステータスの件数を取得
    public static function getOrderSpecifyOrderStatus($operator, $order_status_id)
    {
        return self::where('order_status_id', $operator, $order_status_id);
    }
    // 指定した期間の出荷済み件数を取得
    public static function getShippedOrder($from, $to)
    {
       return self::where('order_status_id', OrderStatusEnum::SHUKKA_ZUMI)
                    ->whereDate('shipping_date', '>=', $from)
                    ->whereDate('shipping_date', '<=', $to);
    }
    // 指定した期間の出荷数量を取得
    public static function getShippedQuantity($from, $to)
    {
       return self::join('order_items', 'order_items.order_control_id', 'orders.order_control_id')
                    ->join('order_item_components', 'order_item_components.order_item_id', 'order_items.order_item_id')
                    ->where('order_status_id', OrderStatusEnum::SHUKKA_ZUMI)
                    ->whereDate('shipping_date', '>=', $from)
                    ->whereDate('shipping_date', '<=', $to)
                    ->selectRaw('COALESCE(SUM(order_item_components.ship_quantity), 0) as total_quantity')
                    ->value('total_quantity');
    }
    // 注文の合計注文数を取得
    public function getTotalOrderQuantity()
    {
        return $this->order_items->sum('order_quantity');
    }
    // 注文の合計出荷数を取得
    public function getTotalShipQuantity()
    {
        return $this->order_items->flatMap(function ($order_item) {
            return $order_item->order_item_components;
        })->sum('ship_quantity');
    }
    // 注文の合計未引当数を取得
    public function getTotalUnallocatedQuantity()
    {
        return $this->order_items->flatMap(function ($order_item) {
            return $order_item->order_item_components;
        })->sum('unallocated_quantity');
    }
    // 渡された配列から受注マークの重複を取り除く
    public static function getOrderMarkFilter($array)
    {
        // 空配列を除外
        $filtered = array_filter($array->toArray(), fn($item) => !empty($item));
        // 重複を除く（連想配列の重複除去はちょっと工夫が必要）
        $unique = [];
        foreach($filtered as $item){
            // 連想配列を文字列に変換してユニーク判定
            $key = serialize($item);
            if(!isset($unique[$key])){
                $unique[$key] = $item;
            }
        }
        // 結果は値だけの配列に
        $unique_array = array_values($unique);
        return $unique_array;
    }
    // 配送希望時間を日本語に変換して返すアクセサ
    public function getDesiredDeliveryTimeJpAttribute()
    {
        return DeliveryTimeZoneEnum::TIME_ZONE_LIST[$this->desired_delivery_time] ?? $this->desired_delivery_time;
    }
    // 出荷完了対象の注文を取得
    public static function getShippingWorkEndTarget($shipping_group_id)
    {
        // 注文ステータスが「作業中」かつ、出荷検品が完了している注文を取得
        return self::where('shipping_group_id', $shipping_group_id)
                ->where('order_status_id', OrderStatusEnum::SAGYO_CHU)
                ->where('is_shipping_inspection_complete', true);
    }
}