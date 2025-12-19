<?php

namespace App\Services\Item\SetItem;

// モデル
use App\Models\SetItem;

class SetItemDeleteService
{
    // セット商品が削除可能か確認
    public function checkDeletable($request)
    {
        // セット商品を取得
        $set_item = SetItem::getSpecify($request->set_item_id)->withCount('order_items')->lockForUpdate()->first();
        // 受注に存在する商品の場合
        if($set_item->order_items_count > 0){
            throw new \RuntimeException('使用されているセット商品のため、削除できません。');
        }
        return $set_item;
    }

    // セット商品を削除
    public function deleteSetItem($set_item)
    {
        // セット商品を削除
        $set_item->delete();
    }
}