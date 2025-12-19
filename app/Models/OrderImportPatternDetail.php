<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderImportPatternDetail extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'order_import_pattern_detail_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'order_import_pattern_id',
        'system_column_name',
        'order_column_name',
        'order_column_index',
        'fixed_value',
    ];
    // order_import_patternsテーブルとのリレーション
    public function order_import_pattern()
    {
        return $this->belongsTo(OrderImportPattern::class, 'order_import_pattern_id', 'order_import_pattern_id');
    }
}
