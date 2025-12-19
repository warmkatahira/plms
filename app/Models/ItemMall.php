<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// トレイト
use App\Traits\ColumnEnChangeTrait;

class ItemMall extends Model
{
    use ColumnEnChangeTrait;

    // テーブル名を定義
    protected $table = 'item_mall';
    // 主キーカラムを変更
    protected $primaryKey = 'item_mall_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'item_id',
        'mall_id',
        'mall_item_code',
        'mall_variation_code',
    ];
    // 指定したレコードを取得
    public static function getSpecifyByMallId($mall_id)
    {
        return self::where('mall_id', $mall_id);
    }
    // itemsテーブルとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            '商品ID',
            'モールID',
            '商品コード',
            '商品JANコード',
            '商品名',
            '商品カテゴリ1',
            '商品カテゴリ2',
            'モール商品コード',
            'モールバリエーションコード',
        ];
    }
    // 英語カラム変換用
    const EN_CHANGE_LIST = [
        '商品ID'                        => 'item_id',
        'モールID'                      => 'mall_id',
        'モール商品コード'              => 'mall_item_code',
        'モールバリエーションコード'    => 'mall_variation_code',
    ];
}
