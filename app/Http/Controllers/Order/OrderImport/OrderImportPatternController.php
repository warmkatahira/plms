<?php

namespace App\Http\Controllers\Order\OrderImport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderImportPattern;
// サービス
use App\Services\Order\OrderImport\OrderImportCommonService;
use App\Services\Order\OrderImport\OrderImportPatternService;
use App\Services\Order\OrderAllocate\AllocateService;
// 例外
use App\Exceptions\OrderImportException;
use App\Enums\OrderImportEnum;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class OrderImportPatternController extends Controller
{
    public function import(Request $request)
    {
        // インスタンス化
        $OrderImportCommonService   = new OrderImportCommonService;
        $AllocateService            = new AllocateService;
        // 変数を初期化
        $import_info        = null;
        $order_num          = null;
        $error_file_name    = null;
        try {
            $result = DB::transaction(function () use ($request, $OrderImportCommonService, &$import_info, &$order_num, &$error_file_name){
                // インスタンス化
                $OrderImportPatternService = new OrderImportPatternService;
                // 変数を初期化
                $message = null;
                // 現在の日時を取得
                $nowDate = CarbonImmutable::now();
                // 受注取込パターンを取得
                $order_import_pattern = OrderImportPattern::getSpecify($request->order_import_pattern_id)->with('order_import_pattern_details')->first();
                // 選択したデータをストレージにインポート
                $import_info = $OrderImportPatternService->importData($request->file('select_file'), $nowDate);
                // インポートしたデータのヘッダーを確認
                $OrderImportPatternService->checkHeader($import_info['save_file_path'], $nowDate, $import_info, $order_import_pattern);
                // order_importsテーブルへ追加する注文情報を配列に格納（同時にバリデーションも実施）
                $order_create_data = $OrderImportPatternService->setOrderArray($import_info['save_file_path'], $nowDate, $order_import_pattern);
                // order_importsテーブルへ追加から自動処理適用までの共通処理
                $order_num = $OrderImportCommonService->processOrderImportCommon($order_create_data, $error_file_name, $message, $nowDate, OrderImportEnum::PATTERN);
                return $order_num;
            });
        } catch (OrderImportException $e){
            // 渡された内容を取得
            $import_info        = $e->getImportInfo();
            $order_num          = $e->getOrderNum();
            $error_file_name    = $e->getErrorFileName();
            // order_import_historiesテーブルへ追加
            $OrderImportCommonService->createOrderImportHistory(OrderImportEnum::PATTERN, $import_info, $order_num, $error_file_name, $e->getMessage());
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