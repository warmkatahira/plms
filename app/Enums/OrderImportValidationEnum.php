<?php

namespace App\Enums;

enum OrderImportValidationEnum
{
    // バリデーションルールを定義
    const RULES = [
        'mall_shipping_method'      => 'nullable|string|max:50',
        'desired_delivery_date'     => 'nullable|date',
        'desired_delivery_time'     => 'nullable|string|max:20',
        'order_no'                  => 'required|string|max:50',
        'order_date'                => 'required|date',
        'order_time'                => 'nullable|date_format:H:i:s',
        'buyer_name'                => 'nullable|string|max:255',
        'ship_name'                 => 'required|string|max:255',
        'ship_postal_code'          => 'required|string|max:8',
        'ship_province_name'        => 'required|string|max:10',
        'ship_address'              => 'required|string|max:255',
        'ship_tel'                  => 'required|string|max:15',
        'ship_id'                   => 'nullable|string|max:20',
        'shipping_fee'              => 'nullable|integer|min:0',
        'payment_amount'            => 'nullable|integer|min:0',
        'order_message'             => 'nullable|string',
        'order_item_system_code'    => 'required|string|max:255',
        'order_item_code'           => 'required|string|max:255',
        'order_item_name'           => 'required|string|max:500',
        'order_quantity'            => 'required|integer|min:1',
        'order_item_price'          => 'nullable|integer|min:0',
        'order_category_id'         => 'required|exists:order_categories,order_category_id',
    ];
    // バリデーションエラーメッセージを定義
    const MESSAGES = [
        'required'              => ':attributeは必須です',
        'date'                  => ':attribute（:input）が日付ではありません',
        'max'                   => ':attribute（:input）は:max文字以内にして下さい',
        'min'                   => ':attribute（:input）は:min以上にして下さい',
        'integer'               => ':attribute（:input）が数値ではありません',
        'exists'                => ':attribute（:input）がシステム内に存在しません',
        'string'                => ':attribute（:input）は文字列にして下さい',
        'boolean'               => ':attribute（:input）が正しくありません',     
    ];
    // バリデーションエラー項目を定義
    const ATTRIBUTES = [
        'mall_shipping_method'      => 'モール配送方法',
        'desired_delivery_date'     => '配送希望日',
        'desired_delivery_time'     => '配送希望時間',
        'order_no'                  => '注文番号',
        'order_date'                => '注文日',
        'order_time'                => '注文時間',
        'buyer_name'                => '注文者名',
        'ship_name'                 => '配送先名',
        'ship_postal_code'          => '配送先郵便番号',
        'ship_province_name'        => '配送先都道府県名',
        'ship_address'              => '配送先住所',
        'ship_tel'                  => '配送先電話番号',
        'ship_id'                   => '配送先ID',
        'shipping_fee'              => '送料',
        'payment_amount'            => '支払金額',
        'order_message'             => '注文メッセージ',
        'order_item_system_code'    => '注文商品システムコード',
        'order_item_code'           => '注文商品コード',
        'order_item_name'           => '注文商品名',
        'order_quantity'            => '注文数',
        'order_item_price'          => '注文商品価格',
        'order_category_id'         => '受注区分',
    ];
}
