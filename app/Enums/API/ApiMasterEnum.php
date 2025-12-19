<?php

namespace App\Enums\API;

enum ApiMasterEnum
{
    // APIアクションマスタの内容を定義
    const API_ACTION_ORDER_GET              = 1;
    const API_ACTION_ITEM_IMAGE_UPDATE      = 2;
    const API_ACTION_STOCK_UPDATE           = 3;
    const API_ACTION_ORDER_CANCEL           = 4;

    const API_ACTION_ORDER_GET_JP           = '受注取得';
    const API_ACTION_ITEM_IMAGE_UPDATE_JP   = '商品画像更新';
    const API_ACTION_STOCK_UPDATE_JP        = '在庫更新';
    const API_ACTION_ORDER_CANCEL_JP        = '注文キャンセル';

    // APIステータスの内容を定義
    const API_STATUS_SUCCESS                = 1;
    const API_STATUS_FAIL                   = 2;
    const API_STATUS_WARNING                = 3;

    const API_STATUS_SUCCESS_JP             = '成功';
    const API_STATUS_FAIL_JP                = '失敗';
    const API_STATUS_WARNING_JP             = '警告';
}
