<?php

namespace App\Enums;

enum OperationLogEnum
{
    // 操作ログの記録を行わないパスを定義
    const NO_OPERATION_RECORD_PATH = [
        // ダッシュボード
        'dashboard',
        // ルート
        'route',
        'route_create',
        'route_update',
        'route_delete',
        'route_detail_update',
        // 操作ログ
        'operation_log',
    ];

    // パスの日本語変換用
    const PATH_JP_CHANGE_LIST = [
        // ルート
        'route_create/create'           => 'ルート追加',
        'route_update/update'           => 'ルート更新',
        'route_delete/delete'           => 'ルート追加',
        'route_detail_update/update'    => 'ルート詳細更新',
    ];

    // パスの日本語を取得
    public static function get_path_jp($key): string
    {
        if(array_key_exists($key, self::PATH_JP_CHANGE_LIST)){
            return self::PATH_JP_CHANGE_LIST[$key];
        }else{
            return $key;
        }
    }

    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            '操作日',
            '操作時間',
            'ユーザー名',
            'IPアドレス',
            'メソッド',
            'パス',
            'パラメータ',
        ];
    }
}