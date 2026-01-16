<?php

namespace App\Http\Controllers\Admin\StatutoryLeave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Admin\StatutoryLeave\StatutoryLeaveUpdateService;
use App\Services\Common\ImportHistoryCreateService;
// 例外
use App\Exceptions\ImportException;
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
                // 現在の日時を取得
                $nowDate = CarbonImmutable::now();
                // 選択したファイルのファイル名を取得
                $import_original_file_name = $StatutoryLeaveUpdateService->getImportOriginalFileName($request->file('select_file'));
                // 選択したファイルをストレージにインポート
                $save_file_path = $StatutoryLeaveUpdateService->importFile($request->file('select_file'));
                // インポートしたデータのヘッダーを確認
                $headers = $StatutoryLeaveUpdateService->checkHeader($save_file_path, $import_original_file_name);
                // 追加するデータを配列に格納（同時にバリデーションも実施）
                $data = $StatutoryLeaveUpdateService->setArrayImportData($save_file_path, $headers, $import_original_file_name);
                // インポートテーブルに追加
                $StatutoryLeaveUpdateService->createArrayImportData($data['create_data']);
                // 従業員を追加
                $StatutoryLeaveUpdateService->createEmployeeByImport();
                // import_historiesテーブルへ追加
                $ImportHistoryCreateService->createImportHistory($import_original_file_name, '追加', null, null);
            });
        } catch (ImportException $e) {
            // 渡された内容を取得
            $message                    = $e->getMessage();
            $import_type                = $e->getImportType();
            $error_file_name            = $e->getErrorFileName();
            $import_original_file_name  = $e->getImportOriginalFileName();
            // import_historiesテーブルへ追加
            $ImportHistoryCreateService->createImportHistory($import_original_file_name, $import_type, $error_file_name, $message);
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