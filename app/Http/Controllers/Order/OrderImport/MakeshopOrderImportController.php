<?php

namespace App\Http\Controllers\Order\OrderImport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Order\OrderImport\OrderImportCommonService;
use App\Services\Order\OrderImport\MakeshopOrderImportService;
use App\Services\Order\OrderAllocate\AllocateService;
// 列挙
use App\Enums\OrderCategoryEnum;
use App\Enums\OrderImportEnum;
// 例外
use App\Exceptions\OrderImportException;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class MakeshopOrderImportController extends Controller
{
    // APIでのインポート
    public function api(Request $request)
    {
        // インスタンス化
        $OrderImportCommonService   = new OrderImportCommonService;
        $AllocateService            = new AllocateService;
        // 変数を初期化
        $order_num          = null;
        $error_file_name    = null;
        try {
            $result = DB::transaction(function () use ($request, $OrderImportCommonService, &$order_num, &$error_file_name){
                // インスタンス化
                $MakeshopOrderImportService  = new MakeshopOrderImportService;
                // 変数を初期化
                $message = null;
                // 現在の日時を取得
                $nowDate = CarbonImmutable::now();
                // order_importsテーブルへ追加する注文情報を配列に格納（同時にバリデーションも実施）
                $order_create_data = $MakeshopOrderImportService->setOrderArray($request, $nowDate, OrderCategoryEnum::MAKESHOP_ID);
                // order_importsテーブルへ追加から自動処理適用までの共通処理
                $order_num = $OrderImportCommonService->processOrderImportCommon($order_create_data, $error_file_name, $message, $nowDate, OrderImportEnum::API);
                return $order_num;
            });
        } catch (OrderImportException $e){
            // 渡された内容を取得
            $import_info        = null;
            $order_num          = $e->getOrderNum();
            $error_file_name    = $e->getErrorFileName();
            // order_import_historiesテーブルへ追加
            $OrderImportCommonService->createOrderImportHistory(OrderImportEnum::API, $import_info, $order_num, $error_file_name, $e->getMessage());
            return redirect()->back()->with([
                'alert_type'    => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        // 引当処理
        $AllocateService->allocateItemAndStockAndOrder(null);
        // 配送方法を更新
        $OrderImportCommonService->updateShippingMethod();
        // 表示するメッセージを作成
        $alert = $OrderImportCommonService->createDispMessage($result);
        return redirect()->back()->with([
            'alert_type'    => $alert['type'],
            'alert_message' => $alert['message'],
        ]);
    }
}