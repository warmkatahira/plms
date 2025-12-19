<?php

namespace App\Services\SystemAdmin\Mall;

// モデル
use App\Models\Mall;
// 列挙
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Str;

class MallUpdateService
{
    // モールを更新
    public function updateMall($request)
    {
        // モールを取得
        $mall = Mall::getSpecify($request->mall_id)->lockForUpdate()->first();
        // モールを更新
        $mall->update([
            'mall_name'             => $request->mall_name,
        ]);
        return $mall;
    }

    // モール画像を削除
    public function deleteMallImage($mall_image_file_name, $request)
    {
        // モール画像が送信されていない場合
        if($request && !$request->hasFile('image_file')){
            // 処理をスキップ
            return;
        }
        // モール画像が「no_image.png」の場合
        if($mall_image_file_name === SystemEnum::DEFAULT_IMAGE_FILE_NAME){
            // 処理をスキップ
            return;
        }
        // モール画像のパスを取得
        $mall_image_path = storage_path('app/public/mall_images/' . $mall_image_file_name);
        // モール画像が存在している場合
        if(file_exists($mall_image_path)){
            // モール画像を削除
            unlink($mall_image_path);
        }
    }

    // モール画像を保存
    public function saveMallImage($request, $mall)
    {
        // モール画像が送られてきていない場合
        if(!$request->hasFile('image_file')){
            // 処理をスキップ
            return;
        }
        // モール画像を取得
        $image = $request->file('image_file');
        // モール画像の拡張子を取得（例: jpg, png）
        $extension = $image->getClientOriginalExtension();
        // 保存するファイル名を設定（uuid + 拡張子）
        $mall_image_file_name = (string) Str::uuid() . '.' . $extension;
        // 保存するパスを設定
        $mall_image_path = storage_path('app/public/mall_images');
        // モール画像を保存
        $image->move($mall_image_path, $mall_image_file_name);
        // モール画像ファイル名を更新
        $mall->update([
            'mall_image_file_name' => $mall_image_file_name,
        ]);
    }
}