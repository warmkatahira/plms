<?php

namespace App\Enums;

enum ItemUploadEnum
{
    // 対象
    const UPLOAD_TARGET_ITEM                        = 'item';
    const UPLOAD_TARGET_ITEM_JP                     = '単品商品';
    const UPLOAD_TARGET_SET_ITEM                    = 'set_item';
    const UPLOAD_TARGET_SET_ITEM_JP                 = 'セット商品';
    const UPLOAD_TARGET_ITEM_MALL_MAPPING           = 'item_mall_mapping';
    const UPLOAD_TARGET_ITEM_MALL_MAPPING_JP        = 'モール×単品商品マッピング';
    const UPLOAD_TARGET_SET_ITEM_MALL_MAPPING       = 'set_item_mall_mapping';
    const UPLOAD_TARGET_SET_ITEM_MALL_MAPPING_JP    = 'モール×セット商品マッピング';
    
    // タイプ
    const UPLOAD_TYPE_CREATE            = 'create';
    const UPLOAD_TYPE_CREATE_JP         = '追加';
    const UPLOAD_TYPE_UPDATE            = 'update';
    const UPLOAD_TYPE_UPDATE_JP         = '更新';

    // 配列にした情報を定義
    const UPLOAD_TARGET_LIST = [
        self::UPLOAD_TARGET_ITEM                    => self::UPLOAD_TARGET_ITEM_JP,
        self::UPLOAD_TARGET_SET_ITEM                => self::UPLOAD_TARGET_SET_ITEM_JP,
        self::UPLOAD_TARGET_ITEM_MALL_MAPPING       => self::UPLOAD_TARGET_ITEM_MALL_MAPPING_JP,
        self::UPLOAD_TARGET_SET_ITEM_MALL_MAPPING   => self::UPLOAD_TARGET_SET_ITEM_MALL_MAPPING_JP,
    ];

    // 配列にした情報を定義
    const UPLOAD_TYPE_LIST = [
        self::UPLOAD_TYPE_CREATE            => self::UPLOAD_TYPE_CREATE_JP,
        self::UPLOAD_TYPE_UPDATE            => self::UPLOAD_TYPE_UPDATE_JP,
    ];

    // 商品追加で必須となるヘッダーを定義
    const REQUIRED_HEADER_ITEM_CREATE = [
        '商品コード',
        '商品JANコード',
        '商品名',
        '商品カテゴリ1',
        '在庫管理',
        '出荷検品要否',
    ];

    // 商品更新で必須となるヘッダーを定義
    const REQUIRED_HEADER_ITEM_UPDATE = [
        '商品コード',
    ];

    // セット商品追加+更新で必須となるヘッダーを定義
    const REQUIRED_HEADER_SET_ITEM_CREATE_UPDATE = [
        'セット商品コード',
        '構成品商品コード',
        '構成数',
    ];

    // モール×単品商品マッピング追加+更新で必須となるヘッダーを定義
    const REQUIRED_HEADER_ITEM_MALL_MAPPING_CREATE_UPDATE = [
        '商品ID',
        'モールID',
        'モール商品コード',
        'モールバリエーションコード',
    ];

    // モール×セット商品マッピング追加+更新で必須となるヘッダーを定義
    const REQUIRED_HEADER_SET_ITEM_MALL_MAPPING_CREATE_UPDATE = [
        'セット商品ID',
        'モールID',
        'モールセット商品コード',
    ];
}