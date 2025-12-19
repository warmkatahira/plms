<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiStatus extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'api_status_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'api_status_name',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('api_status_id', 'asc');
    }
}
