<?php

namespace App\Enums;

enum IgnoreEmployeeEnum: string
{
    case OOKI_NAOYA     = '1715';
    case KIMURA_YUKI    = '5001';

    // 除外する従業員番号の一覧を配列で返す
    public static function getIgnoreEmployeeNos(): array
    {
        return array_column(self::cases(), 'value');
    }
}