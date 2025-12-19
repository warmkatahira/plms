<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetItemImport extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'set_item_import_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'set_item_code',
        'set_item_name',
        'component_item_code',
        'component_item_id',
        'component_quantity',
    ];
    // set_itemsテーブルとのリレーション
    public function set_item()
    {
        return $this->belongsTo(SetItem::class, 'set_item_code', 'set_item_code');
    }
}
