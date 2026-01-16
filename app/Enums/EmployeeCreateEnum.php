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
}