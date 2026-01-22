<?php

namespace App\Http\Controllers\Admin\PaidLeave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Admin\PaidLeave\PaidLeaveUpdateService;
use App\Services\Common\ImportHistoryCreateService;
use App\Services\Common\ImportService;
// 例外
use App\Exceptions\ImportException;
// 列挙
use App\Enums\PaidLeaveUpdateEnum;
use App\Enums\ImportEnum;
// その他
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class PaidLeaveUpdateController extends Controller
{
    public function import(Request $request)
    {
        // インスタンス化
        $ImportHistoryCreateService = new ImportHistoryCreateService;
        try {
            DB::transaction(function () use ($request, $ImportHistoryCreateService) {
                // インスタンス化
                $PaidLeaveUpdateService = new PaidLeaveUpdateService;
                $ImportService = new ImportService;
                // 現在の日時を取得
                $nowDate = CarbonImmutable::now();
                // 選択したファイルのファイル名を取得
                $import_original_file_name = $ImportService->getImportOriginalFileName($request->file('select_file'));
                // 選択したファイルをストレージにインポート
                $save_file_path = $ImportService->importFile($request->file('select_file'), 'paid_leavee_update_import_data');
                // インポートしたデータのヘッダーを確認
                $headers = $ImportService->checkHeader($save_file_path, $import_original_file_name, PaidLeaveUpdateEnum::REQUIRE_HEADER, PaidLeaveUpdateEnum::EN_CHANGE_LIST, ImportEnum::IMPORT_TYPE_UPDATE);
                // 追加するデータを配列に格納（同時にバリデーションも実施）
                $data = $PaidLeaveUpdateService->setArrayImportData($save_file_path, $headers, $import_original_file_name);
                // インポートテーブルに追加
                $PaidLeaveUpdateService->createArrayImportData($data['create_data']);
                // 有給情報を更新
                $error_file_name = $PaidLeaveUpdateService->updatePaidLeave();
                // 表示するメッセージを調整
                if(is_null($error_file_name)){
                    $message = null;
                }else{
                    $message = '更新できなかった従業員が存在します。';
                }
                // import_historiesテーブルへ追加
                $ImportHistoryCreateService->createImportHistory($import_original_file_name, ImportEnum::IMPORT_PROCESS_STATUTORY_LEAVE, ImportEnum::IMPORT_TYPE_UPDATE, $error_file_name, $message);
            });
        } catch (ImportException $e) {
            // 渡された内容を取得
            $message                    = $e->getMessage();
            $import_process             = $e->getImportProcess();
            $import_type                = $e->getImportType();
            $error_file_name            = $e->getErrorFileName();
            $import_original_file_name  = $e->getImportOriginalFileName();
            // import_historiesテーブルへ追加
            $ImportHistoryCreateService->createImportHistory($import_original_file_name, $import_process, $import_type, $error_file_name, $message);
            return redirect()->route('import_history.index')->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('import_history.index')->with([
            'alert_type' => 'success',
            'alert_message' => '有給情報更新(取込)が完了しました。',
        ]);
    }
}