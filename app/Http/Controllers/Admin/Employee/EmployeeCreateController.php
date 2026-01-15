<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// モデル
use App\Models\Base;
// サービス
use App\Services\Admin\Employee\EmployeeCreateService;
// リクエスト
use App\Http\Requests\Admin\Employee\EmployeeCreateRequest;
// 例外
use App\Exceptions\EmployeeImportException;
// 列挙
use App\Enums\WorkingHoursEnum;
// その他
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

class EmployeeCreateController extends Controller
{
    public function index()
    {
        // ページヘッダーをセッションに格納
        session(['page_header' => '従業員追加']);
        // 営業所を取得
        $bases = Base::getAll()->get();
        // 1日あたりの時間数を取得
        $daily_working_hours = WorkingHoursEnum::DAILY_WORKING_HOURS;
        // 半日あたりの時間数を取得
        $half_day_working_hours = WorkingHoursEnum::HALF_DAY_WORKING_HOURS;
        return view('admin.employee.create')->with([
            'bases' => $bases,
            'daily_working_hours' => $daily_working_hours,
            'half_day_working_hours' => $half_day_working_hours,
        ]);
    }

    public function create(EmployeeCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                // インスタンス化
                $EmployeeCreateService = new EmployeeCreateService;
                // 従業員を追加
                $EmployeeCreateService->createEmployee($request);
            });
        } catch (\Exception $e){
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('employee.index')->with([
            'alert_type' => 'success',
            'alert_message' => '従業員を追加しました。',
        ]);
    }

    public function import(Request $request)
    {
        // インスタンス化
        $EmployeeCreateService = new EmployeeCreateService;
        try {
            DB::transaction(function () use ($request, $EmployeeCreateService) {
                // 現在の日時を取得
                $nowDate = CarbonImmutable::now();
                // 選択したファイルのファイル名を取得
                $import_original_file_name = $EmployeeCreateService->getImportOriginalFileName($request->file('select_file'));
                // 選択したファイルをストレージにインポート
                $save_file_path = $EmployeeCreateService->importFile($request->file('select_file'));
                // インポートしたデータのヘッダーを確認
                $headers = $EmployeeCreateService->checkHeader($save_file_path, $import_original_file_name);
                // 追加するデータを配列に格納（同時にバリデーションも実施）
                $data = $EmployeeCreateService->setArrayImportData($save_file_path, $headers, $import_original_file_name);
                // インポートテーブルに追加
                $EmployeeCreateService->createArrayImportData($data['create_data']);
                // 従業員を追加
                $EmployeeCreateService->createEmployeeByImport();
                // import_historiesテーブルへ追加
                $EmployeeCreateService->createImportHistory($import_original_file_name, '追加', null, null);
            });
        } catch (EmployeeImportException $e) {
            // 渡された内容を取得
            $message                    = $e->getMessage();
            $import_type                = $e->getImportType();
            $error_file_name            = $e->getErrorFileName();
            $import_original_file_name  = $e->getImportOriginalFileName();
            // import_historiesテーブルへ追加
            $EmployeeCreateService->createImportHistory($import_original_file_name, $import_type, $error_file_name, $message);
            return redirect()->route('import_history.index')->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->route('import_history.index')->with([
            'alert_type' => 'success',
            'alert_message' => '従業員追加(取込)が完了しました。',
        ]);
    }
}