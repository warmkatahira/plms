<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// その他
use Carbon\CarbonImmutable;

class ShippingWorkEndHistory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'shipping_work_end_history_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'target_count',
        'is_successful',
        'message',
        'error_file_name',
    ];
    // 「is_successful」に基づいて、有効 or 無効を返すアクセサ
    public function getStatusTextAttribute(): string
    {
        return $this->is_successful ? '成功' : '失敗';
    }
}
