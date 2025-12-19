<?php

namespace App\Http\Controllers\SystemAdmin\Mall;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\SystemAdmin\Mall\MallCreateService;
use App\Services\SystemAdmin\Mall\MallUpdateService;
// リクエスト
use App\Http\Requests\SystemAdmin\Mall\MallCreateRequest;
// その他
use Illuminate\Support\Facades\DB;

class MallCreateController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'モール追加']);
        return view('system_admin.mall.create')->with([
        ]);
    }

    public function create(MallCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $MallCreateService = new MallCreateService;
                $MallUpdateService = new MallUpdateService;
                // モールを追加
                $mall = $MallCreateService->createMall($request);
                // モール画像を保存
                $MallUpdateService->saveMallImage($request, $mall);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('mall.index')->with([
            'alert_type' => 'success',
            'alert_message' => 'モールを追加しました。',
        ]);
    }
}