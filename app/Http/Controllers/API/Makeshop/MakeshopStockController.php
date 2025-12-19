<?php

namespace App\Http\Controllers\API\Makeshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\API\Makeshop\MakeshopStockUpdateService;
use App\Services\API\ApiCommonService;
use App\Services\API\Makeshop\MakeshopApiCommonService;
// 列挙
use App\Enums\MallEnum;
use App\Enums\API\ApiMasterEnum;
// その他
use Illuminate\Support\Facades\DB;

class MakeshopStockController extends Controller
{
    // 在庫を更新
    public function update_stock()
    {
        try {
            DB::transaction(function (){
                // インスタンス化
                $MakeshopStockUpdateService = new MakeshopStockUpdateService;
                $ApiCommonService = new ApiCommonService;
                $MakeshopApiCommonService = new MakeshopApiCommonService;
                // 在庫更新対象を取得
                $stocks = $MakeshopStockUpdateService->getStockUpdateItem();
                // 在庫を更新
                $errors = $MakeshopStockUpdateService->updateStock($stocks);
                // レスポンスエラーをファイルに出力
                $file_name = $MakeshopApiCommonService->exportResponseError($errors, '在庫更新エラー');
                // API履歴を追加
                $ApiCommonService->createApiHistory($file_name, MallEnum::MAKESHOP, ApiMasterEnum::API_ACTION_STOCK_UPDATE);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '在庫を更新しました。',
        ]);
    }
}