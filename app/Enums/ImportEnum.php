<?php

namespace App\Enums;

enum ImportEnum
{
    // 取込区分
    const IMPORT_TYPE_CREATE    = '追加';
    const IMPORT_TYPE_UPDATE    = '更新';

    // 取込処理名
    const IMPORT_PROCESS_EMPLOYEE        = '従業員';
    const IMPORT_PROCESS_STATUTORY_LEAVE = '義務情報';
}