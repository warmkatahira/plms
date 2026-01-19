<?php

namespace App\Services\SystemAdmin\WorkingHour;

// モデル
use App\Models\WorkingHour;
// 列挙
use App\Enums\WorkingHourEnum;

class WorkingHourDeleteService
{
    // 削除可能か確認
    public function checkDeletable($request)
    {
        // 削除対象を取得
        $working_hour = WorkingHour::find($request->working_hour_id);
        // 勤務区分が「1日」の場合
        if(WorkingHourEnum::WORKING_TYPE_DAILY === $working_hour->working_type){
            // 適用されている従業員が存在している場合
            if($working_hour->getPaidLeaveRecord($working_hour->working_hour, $working_hour->working_type, WorkingHourEnum::DAILY_WORKING_HOURS) > 0){
                throw new \RuntimeException('設定されている勤務時間数のため、削除できません。');
            }
        }
        // 勤務区分が「半日」の場合
        if(WorkingHourEnum::WORKING_TYPE_HALF === $working_hour->working_type){
            // 適用されている従業員が存在している場合
            if($working_hour->getPaidLeaveRecord($working_hour->working_hour, $working_hour->working_type, WorkingHourEnum::HALF_DAY_WORKING_HOURS) > 0){
                throw new \RuntimeException('設定されている勤務時間数のため、削除できません。');
            }
        }
    }

    // 勤務時間数を削除
    public function deleteWorkingHour($request)
    {
        // 勤務時間数を削除
        WorkingHour::where('working_hour_id', $request->working_hour_id)->delete();
    }
}