<?php

namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Admin\Route\RouteDeleteService;
// リクエスト
use App\Http\Requests\Admin\Route\RouteDeleteRequest;
// その他
use Illuminate\Support\Facades\DB;

class RouteDeleteController extends Controller
{
    public function delete(RouteDeleteRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $RouteDeleteService = new RouteDeleteService;
                // ルートを削除
                $RouteDeleteService->deleteRoute($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => 'ルートを削除しました。',
        ]);
    }
}