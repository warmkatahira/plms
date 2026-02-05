<?php

namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Admin\Route\RouteCopyService;
// リクエスト
use App\Http\Requests\Admin\Route\RouteCopyRequest;
// その他
use Illuminate\Support\Facades\DB;

class RouteCopyController extends Controller
{
    public function copy(RouteCopyRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $RouteCopyService = new RouteCopyService;
                // ルートを複製
                $RouteCopyService->copyRoute($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect(session('back_url_1'))->with([
            'alert_type' => 'success',
            'alert_message' => 'ルートを複製しました。',
        ]);
    }
}