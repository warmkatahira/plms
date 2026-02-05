<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardingLocation extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'boarding_location_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'location_name',
        'location_memo',
        'is_active',
        'sort_order',
    ];
    // 主キーで検索するスコープ
    public function scopeByPk($query, $id)
    {
        return $query->whereKey($id);
    }
    // 並び替えて取得
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
    // 利用可否の文字列を返すアクセサ
    public function getIsActiveTextAttribute()
    {
        return $this->is_active ? '利用可' : '利用不可';
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            '利用可否',
            '場所名',
            '場所メモ',
            '並び順',
            '最終更新日時',
        ];
    }
}
