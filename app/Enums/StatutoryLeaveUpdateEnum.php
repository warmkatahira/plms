<?php

namespace App\Enums;

enum StatutoryLeaveUpdateEnum
{
    // 義務情報更新に必要なカラムを定義
    const REQUIRE_HEADER = [
        '社員CD',
        '社員名',
        '義務の日数',
        '義務の期限',
        '義務の残日数',
    ];

    // 英語カラム変換用
    const EN_CHANGE_LIST = [
        '社員CD'        => 'employee_no',
        '社員名'        => 'user_name',
        '義務の日数'    => 'statutory_leave_days',
        '義務の期限'    => 'statutory_leave_expiration_date',
        '義務の残日数'  => 'statutory_leave_remaining_days',
    ];
}