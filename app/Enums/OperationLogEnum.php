<?php

namespace App\Enums;

enum OperationLogEnum
{
    // 操作ログの記録を行わないパスを定義
    const NO_OPERATION_RECORD_PATH = [
        // 従業員一覧
        'employee_update',
        'employee_create',
        // 営業所
        'base_update',
        'base_create',
        // ユーザー
        'user_update',
        'user_create',
        // 勤務時間数
        'working_hour_create',
        // 操作ログ
        'operation_log',
        'operation_log_download/download',
    ];

    // パスの日本語変換用
    const PATH_JP_CHANGE_LIST = [
        // ダッシュボード
        'dashboard' => 'ダッシュボード',
        // 従業員一覧
        'employee'                      => '従業員一覧',
        'employee_update/update'        => '従業員更新',
        'employee_download/download'    => '従業員ダウンロード',
        'employee_create/create'        => '従業員追加(入力)',
        'employee_create/import'        => '従業員追加(取込)',
        'paid_leave_update/import'      => '有給情報更新(取込)',
        'statutory_leave_update/import' => '義務情報更新(取込)',
        'other_update/import'           => 'その他情報更新(取込)',
        // 権限
        'role'                          => '権限',
        // 営業所
        'base'                          => '営業所',
        'base_update/update'            => '営業所更新',
        'base_create/create'            => '営業所追加',
        // ユーザー
        'user'                          => 'ユーザー',
        'user_update/update'            => 'ユーザー更新',
        'user_create/create'            => 'ユーザー追加',
        // 勤務時間数
        'working_hour'                  => '勤務時間数',
        'working_hour_delete/delete'    => '勤務時間数削除',
        'working_hour_create/create'    => '勤務時間数追加',
        // 取込履歴
        'import_history' => '取込履歴',
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