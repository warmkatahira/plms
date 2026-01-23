<?php

namespace App\Enums;

enum OtherUpdateEnum
{
    // その他情報更新に必要なカラムを定義
    const REQUIRE_HEADER = [
        '社員CD',
        '1日あたりの時間数',
        '半日あたりの時間数',
        '義務残日数自動更新',
    ];

    // 英語カラム変換用
    const EN_CHANGE_LIST = [
        '社員CD'                    => 'employee_no',
        '1日あたりの時間数'         => 'daily_working_hours',
        '半日あたりの時間数'        => 'half_day_working_hours',
        '義務残日数自動更新'        => 'is_auto_update_statutory_leave_remaining_days',
    ];
}