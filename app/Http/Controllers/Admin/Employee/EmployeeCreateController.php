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
        return view('admin.employee.create')->with([
            'bases' => $bases,
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
        try {
            DB::transaction(function () use ($request) {
                // 現在の日時を取得
                $nowDate = CarbonImmutable::now();
                // インスタンス化
                $EmployeeCreateService = new EmployeeCreateService;
                // 選択したデータをストレージにインポート
                $save_file_path = $EmployeeCreateService->importData($request->file('select_file'));
                // インポートしたデータのヘッダーを確認
                $headers = $EmployeeCreateService->checkHeader($save_file_path);
                // 追加するデータを配列に格納（同時にバリデーションも実施）
                $data = $EmployeeCreateService->setArrayImportData($save_file_path, $headers);
                // バリデーションエラー配列の中にnull以外があれば、エラー情報を出力
                if (count(array_filter($data['validation_error'])) != 0) {
                    // セッションにエラー情報を格納
                    session(['tracking_no_upload_error' => array(['エラー情報' => $data['validation_error'], 'アップロード日時' => $nowDate])]);
                    throw new \Exception('データが正しくないため、アップロードできませんでした。');
                }
                // インポートテーブルに追加
                $EmployeeCreateService->createArrayImportData($data['create_data']);
                // 従業員を追加
                $EmployeeCreateService->createEmployeeByImport();
            });
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'alert_type' => 'error',
                'alert_message' => $e->getMessage(),
            ]);
        }
        return redirect()->back()->with([
            'alert_type' => 'success',
            'alert_message' => '従業員追加(取込)が完了しました。',
        ]);
    }
}