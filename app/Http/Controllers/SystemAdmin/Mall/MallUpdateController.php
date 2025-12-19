<?php

namespace App\Http\Controllers\SystemAdmin\Mall;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Mall;
// サービス
use App\Services\SystemAdmin\Mall\MallUpdateService;
// リクエスト
use App\Http\Requests\SystemAdmin\Mall\MallUpdateRequest;
// その他
use Illuminate\Support\Facades\DB;

class MallUpdateController extends Controller
{
    public function index(Request $request)
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => 'モール更新']);
        // モールを取得
        $mall = Mall::getSpecify($request->mall_id)->first();
        return view('system_admin.mall.update')->with([
            'mall' => $mall,
        ]);
    }

    public function update(MallUpdateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $MallUpdateService = new MallUpdateService;
                // モールを更新
                $mall = $MallUpdateService->updateMall($request);
                // モール画像を削除
                $MallUpdateService->deleteMallImage($mall->mall_image_file_name, $request);
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
            'alert_message' => 'モールを更新しました。',
        ]);
    }
}