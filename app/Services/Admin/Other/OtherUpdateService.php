<?php

namespace App\Services\Admin\Other;

// モデル
use App\Models\User;
use App\Models\EmployeeImport;
// サービス
use App\Services\Common\ImportErrorCreateService;
use App\Services\Common\NotUpdateInfoCreateService;
// 列挙
use App\Enums\OtherUpdateEnum;
use App\Enums\ImportEnum;
use App\Enums\WorkingHourEnum;
// 例外
use App\Exceptions\ImportException;
// ヘルパー
use App\Helpers\ColumnChangeHelper;
// その他
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;
use Carbon\CarbonImmutable;
use Illuminate\Validation\Rule;

class OtherUpdateService
{
    // 追加するデータを配列に格納（同時にバリデーションも実施）
    public function setArrayImportData($save_file_path, $headers, $import_original_file_name)
    {
        // 選択したデータの拡張子を取得（例: csv, xlsx）
        $extension = strtolower(pathinfo($import_original_file_name, PATHINFO_EXTENSION));
        // データの情報を取得
        $all_line = (new FastExcel)->import($save_file_path);
        // 配列をセット
        $create_data = [];
        // 取得したレコードの分だけループ
        foreach($all_line as $index => $line){
            // UTF-8形式に変換した1行分のデータを取得
            if($extension === 'csv'){
                $line = mb_convert_encoding($line, 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win');
            }
            // 1行のデータを格納する配列をセット
            $param = [];
            // 追加先テーブルのカラム名に合わせて配列を整理
            foreach($line as $key => $value){
                // 英語カラムを定義している配列から取得
                $en_column = ColumnChangeHelper::column_en_change($key, OtherUpdateEnum::EN_CHANGE_LIST);
                // カラムが空ではない場合
                if($en_column != ''){
                    // 値の調整を行う
                    $adjustment_value = $this->valueAdjustment($key, $value);
                    // 配列に変換した英語カラムを格納
                    $param[$en_column] = $adjustment_value;
                }
            }
            // 追加用の配列に整理した情報を格納
            $create_data[] = $param;
        }
        // バリデーション
        $validation_error = $this->commonValidation($create_data, $headers);
        // バリデーションエラーにnull以外があれば、エラー情報を出力
        if(count(array_filter($validation_error)) != 0){
            // インスタンス化
            $ImportErrorCreateService   = new ImportErrorCreateService;
            // エラー情報のファイルを作成
            $error_file_name = $ImportErrorCreateService->createImportError(ImportEnum::IMPORT_PROCESS_OTHER.'取込エラー', $validation_error);
            throw new ImportException("データが正しくないため、取り込みできませんでした。", ImportEnum::IMPORT_PROCESS_OTHER, ImportEnum::IMPORT_TYPE_UPDATE, $error_file_name, $import_original_file_name);
        }
        return compact('create_data', 'validation_error');
    }

    // 値の調整を行う
    public function valueAdjustment($key, $value)
    {
        // 特定のキーのみ値の調整を行う
        switch($key){
            case '社員CD':
                // シングルクォーテーションを取り除いている
                $adjustment_value = str_replace(array("'"), "", $value);
                break;
            case '義務残日数自動更新':
                // 無効を「0」、有効を「1」に変換
                $adjustment_value = $value === '無効' ? 0 : ($value === '有効' ? 1 : $value);
                break;
            default:
                // 何もしない
                $adjustment_value = $value;
                break;
        }
        return $adjustment_value === '' ? null : $adjustment_value;
    }

    // バリデーション
    public function commonValidation($params, $headers)
    {
        // ルールを格納する配列をセット
        $rules = [];
        // バリデーションルールを定義
        foreach($headers as $column){
            switch ($column){
                case 'employee_no':
                    $rules += ['*.'.$column => 'required|string|exists:users,employee_no'];
                    break;
                case 'daily_working_hours':
                    $rules += [
                        '*.' . $column => [
                            'required',
                            Rule::exists('working_hours', 'working_hour')->where('working_type', WorkingHourEnum::WORKING_TYPE_DAILY),
                        ],
                    ];
                    break;
                case 'half_day_working_hours':
                    $rules += [
                        '*.' . $column => [
                            'required',
                            Rule::exists('working_hours', 'working_hour')->where('working_type', WorkingHourEnum::WORKING_TYPE_HALF),
                        ],
                    ];
                    break;
                case 'is_auto_update_statutory_leave_remaining_days':
                    $rules += ['*.'.$column => 'required|boolean'];
                    break;
                default:
                    break;
            }
        }
        // バリデーションエラーメッセージを定義
        $messages = [
            'required'                      => ':attributeは必須です。',
            'exists'                        => ':attribute（:input）はシステムに存在しません。',
            'boolean'                       => ':attribute（:input）が正しくありません。',
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            '*.employee_no'                                     => '社員CD',
            '*.daily_working_hours'                             => '1日あたりの時間数',
            '*.half_day_working_hours'                          => '半日あたりの時間数',
            '*.is_auto_update_statutory_leave_remaining_days'   => '義務残日数自動更新',
        ];
        // バリデーション実施
        return $this->processValidation($params, $rules, $messages, $attributes);
    }

    // バリデーション実施
    public function processValidation($params, $rules, $messages, $attributes)
    {
        // バリデーションエラーを格納する配列を初期化
        $validation_error = [];
        // バリデーション実施
        $validator = Validator::make($params, $rules, $messages, $attributes);
        // バリデーションエラーの分だけループ
        foreach($validator->errors()->getMessages() as $key => $value){
            // 値を「.」で分割
            $key_explode = explode('.', $key);
            // メッセージを格納
            $validation_error[] = [
                'エラー行数' => ($key_explode[0] + 2) . '行目',
                'エラー内容' => $value[0],
            ];
        }
        return $validation_error;
    }
    
    // インポートテーブルに追加
    public function createArrayImportData($create_data)
    {
        // テーブルをロック
        EmployeeImport::select()->lockForUpdate()->get();
        // 追加先のテーブルをクリア
        EmployeeImport::query()->delete();
        // 追加用の配列に入っている情報をテーブルに追加
        EmployeeImport::insert($create_data);
    }

    // その他情報を更新
    public function updateOther()
    {
        // 更新対象でステータスが無効の従業員情報を格納する配列を初期化
        $not_update_employees = [];
        // 更新する情報の分だけループ処理
        foreach(EmployeeImport::all() as $employee_import){
            // 従業員を取得
            $employee = User::where('employee_no', $employee_import->employee_no)->lockForUpdate()->first();
            // ステータスが無効の場合
            if(!$employee->status){
                // 配列に追加
                $not_update_employees[] = [
                    $employee->employee_no,
                    $employee->user_name,
                ];
                // 次のループ処理へ
                continue;
            }
            // その他情報を更新
            $employee->update([
                'is_auto_update_statutory_leave_remaining_days' => $employee_import->is_auto_update_statutory_leave_remaining_days,
            ]);
            $employee->paid_leave->update([
                'daily_working_hours'       => $employee_import->daily_working_hours,
                'half_day_working_hours'    => $employee_import->half_day_working_hours,
            ]);
        }
        // 配列が空ではない場合
        if(!empty($not_update_employees)){
            // インスタンス化
            $NotUpdateInfoCreateService = new NotUpdateInfoCreateService;
            // 更新しなかった従業員情報を出力
            return $NotUpdateInfoCreateService->createNotUpdateInfo($not_update_employees);
        }
        return null;
    }
}