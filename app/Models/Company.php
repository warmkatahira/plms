<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'company_id';
    // オートインクリメント無効化
    public $incrementing = false;
    // 操作可能なカラムを定義
    protected $fillable = [
        'company_id',
        'company_name',
        'sort_order',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('sort_order', 'asc');
    }
    // 指定したレコードを取得
    public static function getSpecify($company_id)
    {
        return self::where('company_id', $company_id);
    }
    // usersテーブルとのリレーション
    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'company_id');
    }
}
