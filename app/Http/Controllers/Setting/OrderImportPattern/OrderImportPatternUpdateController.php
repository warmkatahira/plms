<?php

namespace App\Http\Controllers\Setting\OrderImportPattern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderImportPattern;
use App\Models\OrderCategory;
// 列挙
use App\Enums\OrderImportPatternEnum;
// サービス
use App\Services\Setting\OrderImportPattern\OrderImportPatternUpdateService;
// リクエスト
use App\Http\Requests\Setting\OrderImportPattern\OrderImportPatternUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class OrderImportPatternUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '受注取込パターン更新']);
        // 受注取込パターンを取得
        $order_import_pattern = OrderImportPattern::getSpecify($request->order_import_pattern_id)
                                    ->with('order_import_pattern_details')
                                    ->with('order_category')
                                    ->first();
        // システムカラムのマッピングを取得
        $sysetm_column_mappings = OrderImportPatternEnum::SYSTEM_COLUMN_MAPPING;
        // 必須のシステムカラムを取得
        $required_system_columns = OrderImportPatternEnum::REQUIRED_SYSTEM_COLUMN;
        // カラム取得方法を取得
        $column_get_methods = OrderImportPatternEnum::COLUMN_GET_METHOD;
        // 受注区分を取得
        $order_categories = OrderCategory::getAll()->get();
        return view('setting.order_import_pattern.update')->with([
            'order_import_pattern' => $order_import_pattern,
            'sysetm_column_mappings' => $sysetm_column_mappings,
            'required_system_columns' => $required_system_columns,
            'column_get_methods' => $column_get_methods,
            'order_categories' => $order_categories,
        ]);
    }

    public function update(OrderImportPatternUpdateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $OrderImportPatternUpdateService = new OrderImportPatternUpdateService;
                // 受注取込パターンを更新
                $OrderImportPatternUpdateService->updateOrderImportPattern($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '受注取込パターンの更新に失敗しました。',
            ]);
        }
        return redirect()->route('order_import_pattern.index')->with([
            'alert_type' => 'success',
            'alert_message' => '受注取込パターンを更新しました。',
        ]);
    }
}