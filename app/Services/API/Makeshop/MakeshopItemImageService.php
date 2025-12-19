<?php

namespace App\Services\API\Makeshop;

// モデル
use App\Models\Item;
use App\Models\ItemMall;
// サービス
use App\Services\Item\Item\ItemDeleteService;
// 列挙
use App\Enums\API\MakeshopEnum;
use App\Enums\MallEnum;
// その他
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MakeshopItemImageService
{
    // 商品画像を取得・更新
    public function updateItemImage($items)
    {
        // インスタンス化
        $ItemDeleteService = new ItemDeleteService;
        // item_mallテーブルでメイクショップのレコードのみを取得
        $item_malls = ItemMall::getSpecifyByMallId(MallEnum::MAKESHOP)->get();
        // レコードが取得できていない場合
        if($item_malls->isEmpty()){
            // 処理をスキップ
            return;
        }
        // item_mallsのレコードの分だけループ処理
        foreach($item_malls as $item_mall){
            // 商品情報に対象の商品が存在していない場合
            if(!isset($items[$item_mall->mall_item_code])){
                // 次のループ処理へ
                continue;
            }
            // 非バリエーション商品の場合
            if(is_null($item_mall->mall_variation_code)){
                // 商品情報を取得(resetを使って最初の要素を取得している)
                $item_info = reset($items[$item_mall->mall_item_code]);
            }
            // バリエーション商品の場合
            if(!is_null($item_mall->mall_variation_code)){
                // 商品情報に対象のバリエーション商品が存在していない場合
                if(!isset($items[$item_mall->mall_item_code][$item_mall->mall_variation_code])){
                    // 次のループ処理へ
                    continue;
                }
                // 商品情報を取得
                $item_info = $items[$item_mall->mall_item_code][$item_mall->mall_variation_code];
            }
            // 商品を取得
            $item = Item::getSpecify($item_mall->item_id)->first();
            // 商品画像を削除
            $ItemDeleteService->deleteItemImage($item->item_image_file_name);
            // 商品画像を保存
            $item_image_file_name = $this->saveItemImage($item_info);
            // 商品画像ファイル名を更新
            $item->update([
                'item_image_file_name' => $item_image_file_name,
            ]);
        }
    }

    // 商品画像を保存
    public function saveItemImage($item_info)
    {
        // item_image_file_pathがNullの場合
        if(is_null($item_info['item_image_file_path'])){
            // 「no_image.png」を返す
            return $item_info['item_image_file_name'];
        }
        // 商品画像データを取得
        $image = Http::get($item_info['item_image_file_path']);
        // 商品画像の拡張子を取得（例: jpg, png）
        $extension = pathinfo(parse_url($item_info['item_image_file_path'], PHP_URL_PATH), PATHINFO_EXTENSION);
        // 保存するファイル名を設定（uuid + 拡張子）
        $item_image_file_name = (string) Str::uuid() . '.' . $extension;
        // 保存先を格納
        $path = "item_images/" . $item_image_file_name;
        // 商品画像を保存
        Storage::disk('public')->put($path, $image->body());
        return $item_image_file_name;
    }
}