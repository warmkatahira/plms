<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// トレイト
use App\Traits\ColumnEnChangeTrait;

class SetItemMall extends Model
{
    use ColumnEnChangeTrait;
    
    // テーブル名を定義
    protected $table = 'set_item_mall';
    // 主キーカラムを変更
    protected $primaryKey = 'set_item_mall_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'set_item_id',
        'mall_id',
        'mall_set_item_code',
    ];
    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            'セット商品ID',
            'モールID',
            'セット商品コード',
            'セット商品名',
            'モールセット商品コード',
        ];
    }
    // 英語カラム変換用
    const EN_CHANGE_LIST = [
        'セット商品ID'                  => 'set_item_id',
        'モールID'                      => 'mall_id',
        'モールセット商品コード'        => 'mall_set_item_code',
    ];
}
