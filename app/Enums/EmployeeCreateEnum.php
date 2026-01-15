<?php

namespace App\Enums;

enum EmployeeCreateEnum
{
    // 従業員追加に必要なカラムを定義
    const REQUIRE_HEADER = [
        '社員CD',
        '社員名',
        'ID',
        'パスワード',
        '営業所',
    ];

    // 英語カラム変換用
    const EN_CHANGE_LIST = [
        '社員CD'        => 'employee_no',
        '社員名'        => 'user_name',
        'ID'            => 'user_id',
        'パスワード'    => 'password',
        '営業所'        => 'short_base_name',
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