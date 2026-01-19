<?php

namespace App\Services\SystemAdmin\WorkingHour;

// モデル
use App\Models\WorkingHour;

class WorkingHourCreateService
{
    // 勤務時間数を追加
    public function createWorkingHour($request)
    {
        // 勤務時間数を追加
        WorkingHour::create([
            'working_type' => $request->working_type,
            'working_hour' => $request->working_hour,
        ]);
    }
}