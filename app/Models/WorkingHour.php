<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// 列挙
use App\Enums\WorkingHourEnum;

class WorkingHour extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'working_hour_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'working_type',
        'working_hour',
    ];
    // 1日あたりの時間数を取得
    public static function getDailyWorkingHours()
    {
        return self::where('working_type', WorkingHourEnum::WORKING_TYPE_DAILY)
                    ->orderBy('working_hour', 'asc');
    }
    // 半日あたりの時間数を取得
    public static function getHalfDayWorkingHours()
    {
        return self::where('working_type', WorkingHourEnum::WORKING_TYPE_HALF)
                    ->orderBy('working_hour', 'asc');
    }
}
