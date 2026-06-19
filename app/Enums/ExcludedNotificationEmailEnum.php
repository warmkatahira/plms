<?php

namespace App\Enums;

enum ExcludedNotificationEmailEnum: string
{
    case H_MURAKAMI = 'h_murakami@warm.co.jp';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}