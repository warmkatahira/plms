<?php

namespace App\Http\Controllers\Setting\OrderImportPattern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Setting\OrderImportPattern\OrderImportPatternDeleteService;
// リクエスト
use App\Http\Requests\Setting\OrderImportPattern\OrderImportPatternDeleteRequest;
// その他
use Illuminate\Support\Facades\DB;

class OrderImportPatternDeleteController extends Controller
{
    public function delete(OrderImportPatternDeleteRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $OrderImportPatternDeleteService = new OrderImportPatternDeleteService;
                // 受注取込パターンを削除
                $OrderImportPatternDeleteService->deleteOrderImportPattern($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '受注取込パターンの削除に失敗しました。',
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '受注取込パターンを削除しました。',
        ]);
    }
}