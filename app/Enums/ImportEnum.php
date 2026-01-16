<?php

namespace App\Enums;

enum ImportEnum
{
    // 取込区分
    const IMPORT_TYPE_CREATE    = '追加';
    const IMPORT_TYPE_UPDATE    = '更新';
}