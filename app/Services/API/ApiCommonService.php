<?php

namespace App\Services\API;

// モデル
use App\Models\ApiHistory;
// 列挙
use App\Enums\API\ApiMasterEnum;
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\DB;

class ApiCommonService
{
    // API履歴を追加
    public function createApiHistory($file_name, $mall_id, $api_action_id)
    {
        // エラーファイルがある場合
        if($file_name){
            // API履歴を追加
            ApiHistory::create([
                'mall_id'           => $mall_id,
                'api_action_id'     => $api_action_id,
                'api_status_id'     => ApiMasterEnum::API_STATUS_FAIL,
                'error_file_name'   => $file_name,
            ]);
        }
        // エラーファイルがない場合
        if(!$file_name){
            // API履歴を追加
            ApiHistory::create([
                'mall_id'           => $mall_id,
                'api_action_id'     => $api_action_id,
                'api_status_id'     => ApiMasterEnum::API_STATUS_SUCCESS,
            ]);
        }
    }
}