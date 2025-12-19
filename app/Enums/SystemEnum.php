<?php

namespace App\Enums;

enum SystemEnum
{
    // 顧客名
    const CUSTOMER_NAME_JP          = 'ブルーイング様';
    const CUSTOMER_NAME_EN          = 'bluing';
    // システム名
    const SYSTEM_NAME_JP            = '事後EC出荷システム';
    // ページネーションの値を定義
    const PAGINATE_OPERATION_LOG    = 200;
    // 初期画像のファイル名を定義
    const DEFAULT_IMAGE_FILE_NAME   = 'no_image.png';
    // 顧客名とシステム名を結合して返す
    public static function getSystemTitle()
    {
        return self::CUSTOMER_NAME_JP . ' ' . self::SYSTEM_NAME_JP;
    }
}