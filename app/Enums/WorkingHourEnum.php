<?php

namespace App\Enums;

enum WorkingHourEnum
{
    const WORKING_TYPE_DAILY    = 'daily';
    const WORKING_TYPE_HALF     = 'half';

    const WORKING_TYPE_DAILY_JP = '1日';
    const WORKING_TYPE_HALF_JP  = '半日';

    const DAILY_WORKING_HOURS       = 'daily_working_hours';
    const HALF_DAY_WORKING_HOURS    = 'half_day_working_hours';

    // 日本語の勤務区分を取得
    public static function get_working_type_jp($value): string
    {
        return match ($value) {
            self::WORKING_TYPE_DAILY => self::WORKING_TYPE_DAILY_JP,
            self::WORKING_TYPE_HALF  => self::WORKING_TYPE_HALF_JP,
        };
    }
}