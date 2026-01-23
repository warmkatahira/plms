<?php

namespace App\Enums;

enum PaidLeaveUpdateEnum
{
    // 義務情報更新に必要なカラムを定義
    const REQUIRE_HEADER = [
        '社員CD',
        '保有日数',
        '取得日数',
        '残日数',
    ];

    // 英語カラム変換用
    const EN_CHANGE_LIST = [
        '社員CD'        => 'employee_no',
        '保有日数'      => 'paid_leave_granted_days',
        '取得日数'      => 'paid_leave_used_days',
        '残日数'        => 'paid_leave_remaining_days',
    ];
}