<?php

namespace App\Http\Controllers\Setting\OrderImportPattern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\OrderCategory;
// 列挙
use App\Enums\OrderImportPatternEnum;
// サービス
use App\Services\Setting\OrderImportPattern\OrderImportPatternCreateService;
// リクエスト
use App\Http\Requests\Setting\OrderImportPattern\OrderImportPatternCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class OrderImportPatternCreateController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '受注取込パターン追加']);
        // システムカラムのマッピングを取得
        $sysetm_column_mappings = OrderImportPatternEnum::SYSTEM_COLUMN_MAPPING;
        // 必須のシステムカラムを取得
        $required_system_columns = OrderImportPatternEnum::REQUIRED_SYSTEM_COLUMN;
        // カラム取得方法を取得
        $column_get_methods = OrderImportPatternEnum::COLUMN_GET_METHOD;
        // 受注区分を取得
        $order_categories = OrderCategory::getAll()->get();
        return view('setting.order_import_pattern.create')->with([
            'sysetm_column_mappings' => $sysetm_column_mappings,
            'required_system_columns' => $required_system_columns,
            'column_get_methods' => $column_get_methods,
            'order_categories' => $order_categories,
        ]);
    }

    public function create(OrderImportPatternCreateRequest $request)
    {
        try{
            DB::transaction(function () use ($request){
                // インスタンス化
                $OrderImportPatternCreateService = new OrderImportPatternCreateService;
                // 受注取込パターンを追加
                $OrderImportPatternCreateService->createOrderImportPattern($request);
            });
        }catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => '受注取込パターンの追加に失敗しました。',
            ]);
        }
        return redirect()->route('order_import_pattern.index')->with([
            'alert_type' => 'success',
            'alert_message' => '受注取込パターンを追加しました。',
        ]);
    }
}