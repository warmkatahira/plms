<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 列挙
use App\Enums\OrderCategoryEnum;

class OrderImport extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_import_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'order_control_id',
        'order_import_date',
        'order_import_time',
        'order_status_id',
        'shipping_base_id',
        'desired_delivery_date',
        'desired_delivery_time',
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
        'order_item_system_code',
        'order_item_code',
        'order_item_name',
        'order_quantity',
        'order_item_price',
        'order_category_id',
        'mall_shipping_method',
        'order_control_id_seq',
    ];
    // 指定したレコードを取得
    public static function getSpecifyByOrderNo($order_no)
    {
        return self::where('order_no', $order_no);
    }
    // 受注インポートに必要なヘッダーを定義
    public static function requireHeaderForOrderImport($order_category_id)
    {
        // makeshopの場合
        if($order_category_id === OrderCategoryEnum::MAKESHOP_ID){
            return [
                '注文番号',
                '日付',
                '配送方法',
                '配送希望日',
                '配送希望時間帯',
                '受取人',
                '郵便番号',
                '住所',
                '受取人の電話番号',
                '商品名',
                'OPTION+商品別特殊表示',
                '商品コード',
                '独自商品コード',
                'オプション独自コード',
                '個数',
            ];
        }
    }
    // ordersテーブルに追加する情報を取得
    public static function createTargetListForOrder($query)
    {
        return $query->select([
            'order_control_id',
            'order_import_date',
            'order_import_time',
            'order_status_id',
            'shipping_base_id',
            'desired_delivery_date',
            'desired_delivery_time',
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
            'order_category_id',
            'mall_shipping_method',
        ]);
    }
    // order_itemsテーブルに追加する情報を取得
    public static function createTargetListForOrderItem($query)
    {
        return $query->select([
            'order_control_id',
            'order_item_system_code',
            'order_item_code',
            'order_item_name',
            'order_quantity',
            'order_item_price',
        ]);
    }
}