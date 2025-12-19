<?php

namespace App\Enums;

enum OrderImportPatternEnum
{
    // システムカラム名を定義
    const SYSTEM_COLUMN_EN_ORDER_NO                 = 'order_no';
    const SYSTEM_COLUMN_EN_ORDER_DATE               = 'order_date';
    const SYSTEM_COLUMN_EN_ORDER_TIME               = 'order_time';
    const SYSTEM_COLUMN_EN_BUYER_NAME               = 'buyer_name';
    const SYSTEM_COLUMN_EN_SHIP_NAME                = 'ship_name';
    const SYSTEM_COLUMN_EN_SHIP_POSTAL_CODE         = 'ship_postal_code';
    const SYSTEM_COLUMN_EN_SHIP_ADDRESS             = 'ship_address';
    const SYSTEM_COLUMN_EN_SHIP_TEL                 = 'ship_tel';
    const SYSTEM_COLUMN_EN_DESIRED_DELIVERY_DATE    = 'desired_delivery_date';
    const SYSTEM_COLUMN_EN_DESIRED_DELIVERY_TIME    = 'desired_delivery_time';
    const SYSTEM_COLUMN_EN_SHIPPING_FEE             = 'shipping_fee';
    const SYSTEM_COLUMN_EN_PAYMENT_AMOUNT           = 'payment_amount';
    const SYSTEM_COLUMN_EN_ORDER_MESSAGE            = 'order_message';
    const SYSTEM_COLUMN_EN_MALL_SHIPPING_METHOD     = 'mall_shipping_method';
    const SYSTEM_COLUMN_EN_ORDER_CONTROL_ID_SEQ     = 'order_control_id_seq';
    const SYSTEM_COLUMN_EN_ORDER_ITEM_SYSTEM_CODE   = 'order_item_system_code';
    const SYSTEM_COLUMN_EN_ORDER_ITEM_CODE          = 'order_item_code';
    const SYSTEM_COLUMN_EN_ORDER_ITEM_NAME          = 'order_item_name';
    const SYSTEM_COLUMN_EN_ORDER_QUANTITY           = 'order_quantity';
    const SYSTEM_COLUMN_EN_ORDER_ITEM_PRICE         = 'order_item_price';

    const SYSTEM_COLUMN_JP_ORDER_NO                 = '注文番号';
    const SYSTEM_COLUMN_JP_ORDER_DATE               = '注文日';
    const SYSTEM_COLUMN_JP_ORDER_TIME               = '注文時間';
    const SYSTEM_COLUMN_JP_BUYER_NAME               = '注文者名';
    const SYSTEM_COLUMN_JP_SHIP_NAME                = '配送先名';
    const SYSTEM_COLUMN_JP_SHIP_POSTAL_CODE         = '配送先郵便番号';
    const SYSTEM_COLUMN_JP_SHIP_ADDRESS             = '配送先住所';
    const SYSTEM_COLUMN_JP_SHIP_TEL                 = '配送先電話番号';
    const SYSTEM_COLUMN_JP_DESIRED_DELIVERY_DATE    = '配送希望日';
    const SYSTEM_COLUMN_JP_DESIRED_DELIVERY_TIME    = '配送希望時間';
    const SYSTEM_COLUMN_JP_SHIPPING_FEE             = '送料';
    const SYSTEM_COLUMN_JP_PAYMENT_AMOUNT           = '支払金額';
    const SYSTEM_COLUMN_JP_ORDER_MESSAGE            = '注文メッセージ';
    const SYSTEM_COLUMN_JP_MALL_SHIPPING_METHOD     = 'モール配送方法';
    const SYSTEM_COLUMN_JP_ORDER_CONTROL_ID_SEQ     = '配送先単位';
    const SYSTEM_COLUMN_JP_ORDER_ITEM_SYSTEM_CODE   = '注文商品システムコード';
    const SYSTEM_COLUMN_JP_ORDER_ITEM_CODE          = '注文商品コード';
    const SYSTEM_COLUMN_JP_ORDER_ITEM_NAME          = '注文商品名';
    const SYSTEM_COLUMN_JP_ORDER_QUANTITY           = '注文数';
    const SYSTEM_COLUMN_JP_ORDER_ITEM_PRICE         = '商品価格';

    // システムカラム名のマッピングを定義
    const SYSTEM_COLUMN_MAPPING = [
        self::SYSTEM_COLUMN_EN_ORDER_NO                 => self::SYSTEM_COLUMN_JP_ORDER_NO,
        self::SYSTEM_COLUMN_EN_ORDER_DATE               => self::SYSTEM_COLUMN_JP_ORDER_DATE,
        self::SYSTEM_COLUMN_EN_ORDER_TIME               => self::SYSTEM_COLUMN_JP_ORDER_TIME,
        self::SYSTEM_COLUMN_EN_BUYER_NAME               => self::SYSTEM_COLUMN_JP_BUYER_NAME,
        self::SYSTEM_COLUMN_EN_SHIP_NAME                => self::SYSTEM_COLUMN_JP_SHIP_NAME,
        self::SYSTEM_COLUMN_EN_SHIP_POSTAL_CODE         => self::SYSTEM_COLUMN_JP_SHIP_POSTAL_CODE,
        self::SYSTEM_COLUMN_EN_SHIP_ADDRESS             => self::SYSTEM_COLUMN_JP_SHIP_ADDRESS,
        self::SYSTEM_COLUMN_EN_SHIP_TEL                 => self::SYSTEM_COLUMN_JP_SHIP_TEL,
        self::SYSTEM_COLUMN_EN_DESIRED_DELIVERY_DATE    => self::SYSTEM_COLUMN_JP_DESIRED_DELIVERY_DATE,
        self::SYSTEM_COLUMN_EN_DESIRED_DELIVERY_TIME    => self::SYSTEM_COLUMN_JP_DESIRED_DELIVERY_TIME,
        self::SYSTEM_COLUMN_EN_SHIPPING_FEE             => self::SYSTEM_COLUMN_JP_SHIPPING_FEE,
        self::SYSTEM_COLUMN_EN_PAYMENT_AMOUNT           => self::SYSTEM_COLUMN_JP_PAYMENT_AMOUNT,
        self::SYSTEM_COLUMN_EN_ORDER_MESSAGE            => self::SYSTEM_COLUMN_JP_ORDER_MESSAGE,
        self::SYSTEM_COLUMN_EN_MALL_SHIPPING_METHOD     => self::SYSTEM_COLUMN_JP_MALL_SHIPPING_METHOD,
        self::SYSTEM_COLUMN_EN_ORDER_CONTROL_ID_SEQ     => self::SYSTEM_COLUMN_JP_ORDER_CONTROL_ID_SEQ,
        self::SYSTEM_COLUMN_EN_ORDER_ITEM_SYSTEM_CODE   => self::SYSTEM_COLUMN_JP_ORDER_ITEM_SYSTEM_CODE,
        self::SYSTEM_COLUMN_EN_ORDER_ITEM_CODE          => self::SYSTEM_COLUMN_JP_ORDER_ITEM_CODE,
        self::SYSTEM_COLUMN_EN_ORDER_ITEM_NAME          => self::SYSTEM_COLUMN_JP_ORDER_ITEM_NAME,
        self::SYSTEM_COLUMN_EN_ORDER_QUANTITY           => self::SYSTEM_COLUMN_JP_ORDER_QUANTITY,
        self::SYSTEM_COLUMN_EN_ORDER_ITEM_PRICE         => self::SYSTEM_COLUMN_JP_ORDER_ITEM_PRICE,
    ];

    // 必須のカラム名を定義
    const REQUIRED_SYSTEM_COLUMN = [
        self::SYSTEM_COLUMN_EN_ORDER_NO,
        self::SYSTEM_COLUMN_EN_SHIP_NAME,
        self::SYSTEM_COLUMN_EN_SHIP_POSTAL_CODE,
        self::SYSTEM_COLUMN_EN_SHIP_ADDRESS,
        self::SYSTEM_COLUMN_EN_SHIP_TEL,
        self::SYSTEM_COLUMN_EN_ORDER_CONTROL_ID_SEQ,
        self::SYSTEM_COLUMN_EN_ORDER_ITEM_SYSTEM_CODE,
        self::SYSTEM_COLUMN_EN_ORDER_ITEM_CODE,
        self::SYSTEM_COLUMN_EN_ORDER_ITEM_NAME,
        self::SYSTEM_COLUMN_EN_ORDER_QUANTITY,
    ];

    // カラム取得方法を定義
    const COLUMN_GET_METHOD_EN_NAME     = 'name';
    const COLUMN_GET_METHOD_EN_INDEX    = 'index';

    const COLUMN_GET_METHOD_JP_NAME     = '名称';
    const COLUMN_GET_METHOD_JP_INDEX    = '位置';

    // カラム取得方法のマッピングを定義
    const COLUMN_GET_METHOD = [
        self::COLUMN_GET_METHOD_EN_NAME   => self::COLUMN_GET_METHOD_JP_NAME,
        self::COLUMN_GET_METHOD_EN_INDEX  => self::COLUMN_GET_METHOD_JP_INDEX,
    ];

    // 指定したキーに対応する値を取得
    public static function getColumnGetMethodJp($key)
    {
        // nullの場合は空文字を返す
        if(is_null($key)){
            return '';
        }
        return self::COLUMN_GET_METHOD[$key] ?? $key;
    }
}
