<?php

namespace App\Services\Item\Item;

// モデル
use App\Models\Item;
use App\Models\Stock;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Http\Request;

class ItemDeleteService
{
    // 商品が削除可能か確認
    public function checkDeletable($request)
    {
        // 商品と在庫を取得
        $item = Item::getSpecify($request->item_id)->withCount('order_items')->lockForUpdate()->first();
        $stocks = Stock::getSpecifyByItemId($request->item_id)->lockForUpdate()->get();
        // 受注に存在する商品の場合
        if($item->order_items_count > 0){
            throw new \RuntimeException('使用されている商品のため、削除できません。');
        }
        // 在庫数が1以上の場合
        if($stocks->where('total_stock', '>=', 1)->isNotEmpty()){
            throw new \RuntimeException('在庫数が1以上あるため、削除できません。');
        }
        return $item;
    }

    // 商品を削除
    public function deleteItem($item)
    {
        // 商品画像のファイル名を取得
        $item_image_file_name = $item->item_image_file_name;
        // 在庫を削除
        Stock::getSpecifyByItemId($item->item_id)->delete();
        // 商品を削除
        $item->delete();
        return $item_image_file_name;
    }

    // 商品画像を削除
    public function deleteItemImage(string $item_image_file_name, ?Request $request = null)
    {
        // 商品画像が送信されていない場合
        if($request && !$request->hasFile('image_file')){
            // 処理をスキップ
            return;
        }
        // 商品画像が「no_image.png」の場合
        if($item_image_file_name === SystemEnum::DEFAULT_IMAGE_FILE_NAME){
            // 処理をスキップ
            return;
        }
        // 商品画像のパスを取得
        $item_image_path = storage_path('app/public/item_images/' . $item_image_file_name);
        // 商品画像が存在している場合
        if(file_exists($item_image_path)){
            // 商品画像を削除
            unlink($item_image_path);
        }
    }
}