<?php

namespace App\Services\Item\SetItem;

// モデル
use App\Models\SetItem;
use App\Models\OrderItemComponent;
// 列挙
use App\Enums\SystemEnum;
use App\Enums\OrderStatusEnum;
// その他
use Illuminate\Support\Str;

class SetItemUpdateService
{
    // セット商品を更新
    public function updateSetItem($request)
    {
        // セット商品を取得
        $set_item = SetItem::getSpecify($request->set_item_id)->lockForUpdate()->first();
        // セット商品を更新
        $set_item->update([
            'set_item_name'         => $request->set_item_name,
            'shipping_method_id'    => $request->shipping_method_id,
        ]);
        return $set_item;
    }

    // セット商品画像を削除
    public function deleteSetItemImage($request, $set_item)
    {
        // セット商品画像が送られてきていない場合
        if(!$request->hasFile('image_file')){
            // 処理をスキップ
            return;
        }
        // 現在設定されているセット商品画像のパスを取得
        $set_item_image_path = storage_path('app/public/set_item_images/' . $set_item->set_item_image_file_name);
        // 現在設定されているセット商品画像が存在しているかつ、初期画像以外の場合
        if(file_exists($set_item_image_path) && $set_item->set_item_image_file_name != SystemEnum::DEFAULT_IMAGE_FILE_NAME){
            // セット商品画像を削除
            unlink($set_item_image_path);
        }
    }

    // セット商品画像を保存
    public function saveSetItemImage($request, $set_item)
    {
        // セット商品画像が送られてきていない場合
        if(!$request->hasFile('image_file')){
            // 処理をスキップ
            return;
        }
        // セット商品画像を取得
        $image = $request->file('image_file');
        // セット商品画像の拡張子を取得（例: jpg, png）
        $extension = $image->getClientOriginalExtension();
        // 保存するファイル名を設定（uuid + 拡張子）
        $set_item_image_file_name = (string) Str::uuid() . '.' . $extension;
        // 保存するパスを設定
        $set_item_image_path = storage_path('app/public/set_item_images');
        // セット商品画像を保存
        $image->move($set_item_image_path, $set_item_image_file_name);
        // セット商品画像ファイル名を更新
        $set_item->update([
            'set_item_image_file_name' => $set_item_image_file_name,
        ]);
    }
}