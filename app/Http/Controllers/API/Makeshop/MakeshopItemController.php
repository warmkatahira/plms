<?php

namespace App\Http\Controllers\API\Makeshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\API\Makeshop\MakeshopItemService;
use App\Services\API\Makeshop\MakeshopItemImageService;
// その他
use Illuminate\Support\Facades\DB;

class MakeshopItemController extends Controller
{
    // 商品画像を取得・更新
    public function update_image()
    {
        try {
            DB::transaction(function (){
                // インスタンス化
                $MakeshopItemService = new MakeshopItemService;
                $MakeshopItemImageService = new MakeshopItemImageService;
                // 商品情報を取得
                $items = $MakeshopItemService->getItem();
                // 商品画像を取得・更新
                $MakeshopItemImageService->updateItemImage($items);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '商品画像を更新しました。',
        ]);
    }
}