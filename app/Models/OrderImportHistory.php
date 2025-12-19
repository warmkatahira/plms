<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// その他
use Carbon\CarbonImmutable;

class OrderImportHistory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_import_history_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'import_type',
        'import_file_name',
        'all_order_num',
        'import_order_num',
        'delete_order_num',
        'error_file_name',
        'message',
    ];
}
