<?php

namespace App\Enums;

enum VehicleEnum
{
    // 定員の検索条件
    const VEHICLE_CAPACITY_4 = '4';
    const VEHICLE_CAPACITY_5 = '5';

    // 定員の検索条件をリスト化
    const VEHICLE_CAPACITY_CONDITION_LIST = [
        self::VEHICLE_CAPACITY_4 => '4人以下',
        self::VEHICLE_CAPACITY_5 => '5人以上',
    ];
}