<?php

namespace App\Http\Controllers\Stock\ReceivingInspection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Base;
// サービス
use App\Services\Stock\ReceivingInspection\ItemIdCodeCheckService;
use App\Services\Stock\ReceivingInspection\ItemIdDeleteService;
use App\Services\Stock\ReceivingInspection\ItemIdChangeService;

class ReceivingInspectionController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '入庫検品']);
        // セッションを初期化
        session(['progress' => array()]);
        // 倉庫を取得
        $bases = Base::getAll()->get();
        return view('stock.receiving_inspection.index')->with([
            'bases' => $bases,
        ]);
    }

    // 商品識別コードが変更された際の処理
    public function ajax_check_item_id_code(Request $request)
    {
        // インスタンス化
        $ItemIdCodeCheckService = new ItemIdCodeCheckService;
        // 商品マスタに存在するか確認し、問題なければ検品数をカウントアップ
        $item = $ItemIdCodeCheckService->check($request);
        // 結果を返す
        return response()->json([
            'error_message' => session('error_message'),
            'add' => session('add'),
            'progress' => session('progress'),
            'quantity' => session('quantity'),
            'item' => $item,
        ]);
    }

    // 検品商品が削除された際の処理
    public function ajax_delete_item_id(Request $request)
    {
        // インスタンス化
        $ItemIdDeleteService = new ItemIdDeleteService;
        // 検品商品を削除
        $ItemIdDeleteService->delete($request);
        // 結果を返す
        return response()->json([
            'item_id' => $request->item_id,
            'progress' => session('progress'),
        ]);
    }

    // 検品商品の変更対象を取得する処理
    public function ajax_get_item_id_change_target(Request $request)
    {
        // インスタンス化
        $ItemIdChangeService = new ItemIdChangeService;
        // 検品商品変更対象を取得
        $items = $ItemIdChangeService->getChangeTarget($request);
        // 結果を返す
        return response()->json([
            'items' => $items,
        ]);
    }

    // 検品商品の変更がされた際の処理
    public function ajax_change_item_id(Request $request)
    {
        // インスタンス化
        $ItemIdChangeService = new ItemIdChangeService;
        // 検品商品の変更
        $item = $ItemIdChangeService->changeItem($request);
        // 結果を返す
        return response()->json([
            'item' => $item,
            'item_id' => $request->item_id,
        ]);
    }
}