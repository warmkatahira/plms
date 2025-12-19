<?php

namespace App\Enums\API;

// 列挙
use App\Enums\ShippingMethodEnum;

enum MakeshopEnum
{
    const APP_ID        = 253;  // アプリID
    const ENDPOINT      = 'https://app-api.makeshop.jp/v1/graphql'; // エンドポイント
    const ACCESS_TOKEN  = 'Bearer PAT.6fdd556b96704f52ec8b0e8c84c7c286d76b0133cc316b703bf921a697d4cc85';    // アクセストークン
    const API_KEY       = '4811fc438711b7a9a56e8c99eb27e005929a770695ae137b6f33481d472372db';   // APIキー
    const INPUT_LIMIT   = 1000;

    // 配送業者コードを定義
    const NONE              = '000';
    const YAMATO_NEKOPOS    = '030';
    const YAMATO_COMPACT    = '029';
    const YAMATO_NORMAL     = '002';
    const SAGAWA_NORMAL     = '003';

    // 配送方法と配送業者コードのマッピング用
    const SHIPPING_METHOD_ID_DELIVERY_COMPANY_CODE_MAPPING = [
        ShippingMethodEnum::YAMATO_NEKOPOS_ID   => self::YAMATO_NEKOPOS,
        ShippingMethodEnum::YAMATO_COMPACT_ID   => self::YAMATO_COMPACT,
        ShippingMethodEnum::YAMATO_NORMAL_ID    => self::YAMATO_NORMAL,
        ShippingMethodEnum::SAGAWA_NORMAL_ID    => self::SAGAWA_NORMAL,
    ];

    // 配送方法から配送業者コードを取得
    public static function delivery_company_code_get($key): string
    {
        // nullの場合は空文字を返す
        if(is_null($key)){
            return '';
        }
        return self::SHIPPING_METHOD_ID_DELIVERY_COMPANY_CODE_MAPPING[$key] ?? $key;
    }

    // レスポンス結果のカラム名を定義
    const RESPONSE_COLUMN_ORDER_NO_SMOOTH               = 'order_no';
    const RESPONSE_COLUMN_SHIP_ID_SMOOTH                = 'ship_id';
    const RESPONSE_COLUMN_MESSAGE_SMOOTH                = 'message';
    const RESPONSE_COLUMN_MALL_ITEM_CODE_SMOOTH         = 'mall_item_code';
    const RESPONSE_COLUMN_ITEM_CODE_SMOOTH              = 'item_code';
    const RESPONSE_COLUMN_MALL_VARIATION_CODE_SMOOTH    = 'mall_variation_code';

    const RESPONSE_COLUMN_ORDER_NO_MAKESHOP             = 'displayOrderNumber';
    const RESPONSE_COLUMN_SHIP_ID_MAKESHOP              = 'deliveryId';
    const RESPONSE_COLUMN_MESSAGE_MAKESHOP              = 'message';
    const RESPONSE_COLUMN_MALL_ITEM_CODE_MAKESHOP       = 'systemCode';
    const RESPONSE_COLUMN_ITEM_CODE_MAKESHOP            = 'customCode';
    const RESPONSE_COLUMN_MALL_VARIATION_CODE_MAKESHOP  = 'variationCustomCode';

    const RESPONSE_COLUMN_ORDER_NO_JP                   = '注文番号';
    const RESPONSE_COLUMN_SHIP_ID_JP                    = '配送先ID';
    const RESPONSE_COLUMN_MESSAGE_JP                    = 'メッセージ';
    const RESPONSE_COLUMN_MALL_ITEM_CODE_JP             = 'モール商品コード';
    const RESPONSE_COLUMN_ITEM_CODE_JP                  = '商品コード';
    const RESPONSE_COLUMN_MALL_VARIATION_CODE_JP        = 'モールバリエーションコード';

    // レスポンス結果のカラム名のマッピング用
    const RESPONSE_COLUMN_MAPPING_SMOOTH_AND_JP = [
        self::RESPONSE_COLUMN_ORDER_NO_SMOOTH               => self::RESPONSE_COLUMN_ORDER_NO_JP,
        self::RESPONSE_COLUMN_SHIP_ID_SMOOTH                => self::RESPONSE_COLUMN_SHIP_ID_JP,
        self::RESPONSE_COLUMN_MALL_ITEM_CODE_SMOOTH         => self::RESPONSE_COLUMN_MALL_ITEM_CODE_JP,
        self::RESPONSE_COLUMN_ITEM_CODE_SMOOTH              => self::RESPONSE_COLUMN_ITEM_CODE_JP,
        self::RESPONSE_COLUMN_MALL_VARIATION_CODE_SMOOTH    => self::RESPONSE_COLUMN_MALL_VARIATION_CODE_JP,
        self::RESPONSE_COLUMN_MESSAGE_SMOOTH                => self::RESPONSE_COLUMN_MESSAGE_JP,
    ];

    const RESPONSE_COLUMN_MAPPING_MAKESHOP_AND_SMOOTH = [
        self::RESPONSE_COLUMN_ORDER_NO_MAKESHOP             => self::RESPONSE_COLUMN_ORDER_NO_SMOOTH,
        self::RESPONSE_COLUMN_SHIP_ID_MAKESHOP              => self::RESPONSE_COLUMN_SHIP_ID_SMOOTH,
        self::RESPONSE_COLUMN_MALL_ITEM_CODE_MAKESHOP       => self::RESPONSE_COLUMN_MALL_ITEM_CODE_SMOOTH,
        self::RESPONSE_COLUMN_ITEM_CODE_MAKESHOP            => self::RESPONSE_COLUMN_ITEM_CODE_SMOOTH,
        self::RESPONSE_COLUMN_MALL_VARIATION_CODE_MAKESHOP  => self::RESPONSE_COLUMN_MALL_VARIATION_CODE_SMOOTH,
        self::RESPONSE_COLUMN_MESSAGE_MAKESHOP              => self::RESPONSE_COLUMN_MESSAGE_SMOOTH,
    ];
}
