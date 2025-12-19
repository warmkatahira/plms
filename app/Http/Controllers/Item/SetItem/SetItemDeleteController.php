<?php

namespace App\Http\Controllers\Item\SetItem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\SetItem;
// サービス
use App\Services\Item\SetItem\SetItemDeleteService;
// リクエスト
use App\Http\Requests\Item\SetItem\SetItemDeleteRequest;
// その他
use Illuminate\Support\Facades\DB;

class SetItemDeleteController extends Controller
{
    public function delete(SetItemDeleteRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $SetItemDeleteService = new SetItemDeleteService;
                // セット商品が削除可能か確認
                $set_item = $SetItemDeleteService->checkDeletable($request);
                // セット商品を削除
                $SetItemDeleteService->deleteSetItem($set_item);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => 'セット商品を削除しました。',
        ]);
    }
}