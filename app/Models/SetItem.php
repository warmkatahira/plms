<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// トレイト
use App\Traits\ColumnEnChangeTrait;

class SetItem extends Model
{
    use ColumnEnChangeTrait;
    
    // 主キーカラムを変更
    protected $primaryKey = 'set_item_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'set_item_code',
        'set_item_name',
        'set_item_image_file_name',
    ];
    // 指定したレコードを取得
    public static function getSpecify($set_item_id)
    {
        return self::where('set_item_id', $set_item_id);
    }
    // set_item_detailsテーブルとのリレーション
    public function set_item_details()
    {
        return $this->hasMany(SetItemDetail::class, 'set_item_id', 'set_item_id')
                    ->orderBy('component_item_id', 'asc');
    }
    // order_itemsとのリレーション
    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'allocated_set_item_id', 'set_item_id');
    }
    // ダウンロード時のヘッダーを定義
    public static function downloadHeader()
    {
        return [
            'セット商品ID',
            'セット商品コード',
            'セット商品名',
            '構成品商品コード',
            '構成数',
            'セット商品画像',
            '最終更新日時',
        ];
    }
    // 英語カラム変換用
    const EN_CHANGE_LIST = [
        'セット商品コード'       => 'set_item_code',
        'セット商品名'          => 'set_item_name',
        '構成品商品コード'       => 'component_item_code',
        '構成数'                => 'component_quantity',
    ];
}