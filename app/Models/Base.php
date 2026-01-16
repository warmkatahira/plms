<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'base_id';
    // オートインクリメント無効化
    public $incrementing = false;
    // 操作可能なカラムを定義
    protected $fillable = [
        'base_id',
        'base_name',
        'short_base_name',
        'sort_order',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('sort_order', 'asc');
    }
    // usersテーブルとのリレーション
    public function users()
    {
        return $this->hasMany(User::class, 'base_id', 'base_id');
    }
    // 省略営業所名から営業所IDを取得
    public static function getBaseIdByShortBaseName($short_base_name)
    {
        // 営業所名から営業所IDを取得
        $base_id = self::where('short_base_name', $short_base_name)->value('base_id');
        // 存在していない場合は、渡された値を返す
        return $base_id ?? $base_name;
    }
}
