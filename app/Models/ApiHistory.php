<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiHistory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'api_history_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'mall_id',
        'api_action_id',
        'api_status_id',
        'error_file_name',
    ];
    // mallsとのリレーション
    public function mall()
    {
        return $this->belongsTo(Mall::class, 'mall_id', 'mall_id');
    }
    // api_actionsとのリレーション
    public function api_action()
    {
        return $this->belongsTo(ApiAction::class, 'api_action_id', 'api_action_id');
    }
    // api_statusesとのリレーション
    public function api_status()
    {
        return $this->belongsTo(ApiStatus::class, 'api_status_id', 'api_status_id');
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            '実行日時',
            'モール名',
            '実行内容',
            'ステータス',
        ];
    }
}
