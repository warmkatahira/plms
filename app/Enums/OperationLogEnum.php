<?php

namespace App\Enums;

enum OperationLogEnum
{
    // 操作ログの記録を行わないパスを定義
    const NO_OPERATION_RECORD_PATH = [
        // ダッシュボード
        'dashboard',
        'dashboard/ajax_get_chart_data',
        // 受注
        'order_import',
        // 出荷
        'order_document',
        'shipping_work_end',
        'shipping_work_end',
        'shipping_inspection',
        // 商品
        'item',
        'set_item',
        'item_upload',
        // 在庫
        'input_stock_operation',
        'input_stock_operation_enter',
        'stock_history',
        // 設定
        // システム管理
        'base',
        'base_create',
        'base_update',
        'user',
        'user_update',
        'operation_log',
        'operation_log_download/download',
    ];

    // パスの日本語変換用
    const PATH_JP_CHANGE_LIST = [
        // 受注
        'order_import/import'                               => '受注取込',
        'order_mgt'                                         => '受注管理',
        'shipping_work_start/enter'                         => '出荷作業開始',
        'order_detail'                                      => '受注詳細',
        'order_detail_update/shipping_method'               => '配送方法更新',
        'order_delete/delete'                               => '受注削除',
        // 出荷
        'shipping_mgt'                                      => '出荷管理',
        'total_picking_list_create/create'                  => 'トータルピッキングリスト発行',
        'kobetsu_picking_list_create/create'                => '個別ピッキングリスト発行',
        'nifuda_create/create'                              => '荷札データ作成',
        'nifuda_download/download'                          => '荷札データダウンロード',
        'tracking_no_import/import'                         => '配送伝票番号取込',
        'shipping_inspection/ajax_check_order_control_id'   => '出荷検品(受注管理IDスキャン)',
        'shipping_inspection/ajax_check_tracking_no'        => '出荷検品(配送伝票番号スキャン)',
        'shipping_inspection/ajax_check_item_id_code'       => '出荷検品(商品識別コードスキャン)',
        'shipping_inspection/complete'                      => '出荷検品完了',
        'shipping_actual_download/download'                 => '出荷実績ダウンロード',
        'shipping_work_end_history'                         => '出荷完了履歴',
        'shipping_work_end/enter'                           => '出荷完了',
        'shipping_history'                                  => '出荷履歴',
        // 商品
        'item_download/download'                => '商品ダウンロード',
        'item_upload/upload'                    => '商品アップロード',
        // 在庫
        'stock/index_by_item'                   => '在庫(商品別)',
        'stock/index_by_stock'                  => '在庫(在庫別)',
        'input_stock_operation_enter/enter'     => '入力在庫数操作',
        'stock_history'                         => '在庫履歴',
        'stock_download/download'               => '在庫ダウンロード',
        // システム管理
        'base_update/update'                    => '倉庫更新',
        'base_create/create'                    => '倉庫追加',
        'user_update/update'                    => 'ユーザー更新',
        'profile'                               => 'プロフィール',
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