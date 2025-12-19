<?php

namespace App\Http\Controllers\Shipping\Nifuda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Shipping\Nifuda\NifudaCreateService;
use App\Services\Shipping\OrderDocument\OrderDocumentService;
// その他
use Illuminate\Support\Facades\DB;

class NifudaCreateController extends Controller
{
    public function create_by_shipping_method(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $NifudaCreateService = new NifudaCreateService;
                // 作成対象を取得
                $orders = $NifudaCreateService->getCreateOrderByShippingMethod($request->shipping_method_id);
                // 荷札データを作成
                $directory_name = $NifudaCreateService->createNifuda($request->shipping_method_id, $orders, session('search_shipping_group_id'));
                // 荷札データ作成履歴を追加
                $NifudaCreateService->createNifudaCreateHistory($request->shipping_method_id, $directory_name, session('search_shipping_group_id'), $orders->count());
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '荷札データを作成しました。',
        ]);
    }

    public function create_by_select_order(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                // インスタンス化
                $NifudaCreateService = new NifudaCreateService;
                $OrderDocumentService = new OrderDocumentService;
                // 作成可能か確認
                $OrderDocumentService->checkCreatable($request->chk);
                // 作成に必要な情報を取得
                $info = $NifudaCreateService->getCreateInfo($request->chk);
                // 作成対象を取得
                $orders = $NifudaCreateService->getCreateOrderBySelectOrder($request->chk);
                // 荷札データを作成
                $directory_name = $NifudaCreateService->createNifuda($info->shipping_method_id, $orders, $info->shipping_group_id);
                // 荷札データ作成履歴を追加
                $NifudaCreateService->createNifudaCreateHistory($info->shipping_method_id, $directory_name, $info->shipping_group_id, $orders->count());
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '荷札データを作成しました。',
        ]);
    }
}
