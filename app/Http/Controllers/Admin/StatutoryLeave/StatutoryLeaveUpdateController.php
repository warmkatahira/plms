<?php

namespace App\Http\Controllers\Admin\StatutoryLeave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Admin\StatutoryLeave\StatutoryLeaveUpdateService;
use App\Services\Common\ImportHistoryCreateService;
use App\Services\Common\ImportService;
// 例外
use App\Exceptions\ImportException;
// 列挙
use App\Enums\StatutoryLeaveUpdateEnum;
use App\Enums\ImportEnum;
// その他
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class StatutoryLeaveUpdateController extends Controller
{
    public function import(Request $request)
    {
        // インスタンス化
        $ImportHistoryCreateService = new ImportHistoryCreateService;
        try {
            DB::transaction(function () use ($request, $ImportHistoryCreateService) {
                // インスタンス化
                $StatutoryLeaveUpdateService = new StatutoryLeaveUpdateService;
                $ImportService = new ImportService;
                // 現在の日時を取得
                $nowDate = CarbonImmutable::now();
                // 選択したファイルのファイル名を取得
                $import_original_file_name = $ImportService->getImportOriginalFileName($request->file('select_file'));
                // 選択したファイルをストレージにインポート
                $save_file_path = $ImportService->importFile($request->file('select_file'), 'statutory_leavee_update_import_data');
                // インポートしたデータのヘッダーを確認
                $headers = $ImportService->checkHeader($save_file_path, $import_original_file_name, StatutoryLeaveUpdateEnum::REQUIRE_HEADER, StatutoryLeaveUpdateEnum::EN_CHANGE_LIST, ImportEnum::IMPORT_TYPE_UPDATE);
                // 追加するデータを配列に格納（同時にバリデーションも実施）
                $data = $StatutoryLeaveUpdateService->setArrayImportData($save_file_path, $headers, $import_original_file_name);
                // インポートテーブルに追加
                $StatutoryLeaveUpdateService->createArrayImportData($data['create_data']);
                // 義務情報を更新
                $StatutoryLeaveUpdateService->updateStatutoryLeave();
                // import_historiesテーブルへ追加
                $ImportHistoryCreateService->createImportHistory($import_original_file_name, ImportEnum::IMPORT_PROCESS_STATUTORY_LEAVE, ImportEnum::IMPORT_TYPE_UPDATE, null, null);
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
            'alert_message' => '義務情報更新(取込)が完了しました。',
        ]);
    }
}