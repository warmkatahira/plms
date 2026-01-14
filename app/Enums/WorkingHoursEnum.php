<?php

namespace App\Enums;

enum WorkingHoursEnum
{
    const H2_75 = '2.75';
    const H3_25 = '3.25';
    const H3_75 = '3.75';
    const H4_75 = '4.75';
    const H5_50 = '5.50';
    const H6_50 = '6.50';

    // 1日あたりの時間数を定義
    const DAILY_WORKING_HOURS = [
        self::H2_75 => self::H2_75,
        self::H3_75 => self::H3_75,
        self::H4_75 => self::H4_75,
        self::H5_50 => self::H5_50,
        self::H6_50 => self::H6_50,
    ];

    // 半日あたりの時間数を定義
    const HALF_DAY_WORKING_HOURS = [
        self::H2_75 => self::H2_75,
        self::H3_25 => self::H3_25,
    ];
}