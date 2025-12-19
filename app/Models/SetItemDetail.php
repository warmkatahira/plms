<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetItemDetail extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'set_item_detail_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'set_item_id',
        'component_item_id',
        'component_quantity',
    ];
    // 指定したレコードを取得
    public static function getSpecifyBySetItemId($set_item_id)
    {
        return self::where('set_item_id', $set_item_id);
    }
    // set_itemsテーブルとのリレーション
    public function set_items()
    {
        return $this->belongsTo(SetItem::class, 'set_item_id', 'set_item_id');
    }
    // itemsテーブルとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class, 'component_item_id', 'item_id');
    }
    // 指定したレコードを取得
    public static function getSpecifyBySetItemCode($set_item_code)
    {
        return self::where('set_item_code', $set_item_code);
    }
}
