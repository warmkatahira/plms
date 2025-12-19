<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImport extends Model
{
    // 主キーカラムを変更
    protected $primaryKey = 'item_import_id';
    // 操作可能なカラムを定義
    protected $fillable = [
        'item_code',
        'item_jan_code',
        'item_name',
        'item_category_1',
        'item_category_2',
        'is_stock_managed',
        'is_shipping_inspection_required',
        'is_hide_on_delivery_note',
        'sort_order',
    ];
    // itemsテーブルとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'item_code');
    }
}