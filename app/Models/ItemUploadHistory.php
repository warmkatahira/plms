<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// 列挙
use App\Enums\ItemUploadEnum;

class ItemUploadHistory extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'item_upload_history_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'job_id',
        'user_no',
        'upload_target',
        'upload_file_path',
        'upload_file_name',
        'error_file_name',
        'upload_type',
        'status',
        'message',
    ];
    // 全てのレコードを取得
    public static function getAll()
    {
        return self::orderBy('item_upload_history_id', 'desc');
    }
    // usersテーブルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_no', 'user_no');
    }
    // 「upload_type」に基づいて、日本語を返すアクセサ
    public function getUploadTypeTextAttribute(): string
    {
        switch($this->upload_type){
            case ItemUploadEnum::UPLOAD_TYPE_CREATE:
                return ItemUploadEnum::UPLOAD_TYPE_CREATE_JP;
            case ItemUploadEnum::UPLOAD_TYPE_UPDATE:
                return ItemUploadEnum::UPLOAD_TYPE_UPDATE_JP;
            default:
                return '不明';
        }
    }
    // 「upload_target」に基づいて、日本語を返すアクセサ
    public function getUploadTargetTextAttribute(): string
    {
        switch($this->upload_target){
            case ItemUploadEnum::UPLOAD_TARGET_ITEM:
                return ItemUploadEnum::UPLOAD_TARGET_ITEM_JP;
            case ItemUploadEnum::UPLOAD_TARGET_SET_ITEM:
                return ItemUploadEnum::UPLOAD_TARGET_SET_ITEM_JP;
            case ItemUploadEnum::UPLOAD_TARGET_ITEM_MALL_MAPPING:
                return ItemUploadEnum::UPLOAD_TARGET_ITEM_MALL_MAPPING_JP;
            default:
                return '不明';
        }
    }
}
