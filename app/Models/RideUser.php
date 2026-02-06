<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RideUser extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'ride_user_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'ride_detail_id',
        'user_no',
    ];
    // usersテーブルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_no', 'user_no');
    }
}
