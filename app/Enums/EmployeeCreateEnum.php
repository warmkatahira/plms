<?php

namespace App\Enums;

enum EmployeeCreateEnum
{
    // 従業員追加に必要なカラムを定義
    const REQUIRE_HEADER = [
        'ステータス',
        '省略営業所名',
        '従業員番号',
        '氏名',
        'ユーザーID',
        '保有日数',
        '残日数',
        '取得日数',
        '1日あたりの時間数',
        '半日あたりの時間数',
        '義務残日数自動更新',
        '義務期限日',
        '義務日数',
        '義務残日数',
    ];

    // 英語カラム変換用
    const EN_CHANGE_LIST = [
        'ステータス'            => 'status',
        '省略営業所名'          => 'short_base_name',
        '従業員番号'            => 'employee_no',
        '氏名'                  => 'user_name',
        'ユーザーID'            => 'user_id',
        '保有日数'              => 'paid_leave_granted_days',
        '残日数'                => 'paid_leave_remaining_days',
        '取得日数'              => 'paid_leave_used_days',
        '1日あたりの時間数'     => 'daily_working_hours',
        '半日あたりの時間数'    => 'half_day_working_hours',
        '義務残日数自動更新'    => 'is_auto_update_statutory_leave_remaining_days',
        '義務期限日'            => 'statutory_leave_expiration_date',
        '義務日数'              => 'statutory_leave_days',
        '義務残日数'            => 'statutory_leave_remaining_days',
    ];

    public static function column_en_change($column)
    {
        // 配列に定義されている項目であれば、値を返す
        if(array_key_exists($column, self::EN_CHANGE_LIST)){
            return self::EN_CHANGE_LIST[$column];
        }
        // 存在していない場合は、空を返す
        return '';
    }
}