<?php

namespace App\Services\Admin\StatutoryLeave;

// モデル
use App\Models\User;
use App\Models\StatutoryLeaveImport;
// サービス
use App\Services\Common\ImportErrorCreateService;
use App\Services\Common\NotUpdateInfoCreateService;
// 列挙
use App\Enums\StatutoryLeaveUpdateEnum;
use App\Enums\ImportEnum;
// 例外
use App\Exceptions\ImportException;
// ヘルパー
use App\Helpers\ColumnChangeHelper;
// その他
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;
use Carbon\CarbonImmutable;

class StatutoryLeaveUpdateService
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
        foreach ($all_line as $line){
            // UTF-8形式に変換した1行分のデータを取得
            if($extension === 'csv'){
                $line = mb_convert_encoding($line, 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win');
            }
            // 1行のデータを格納する配列をセット
            $param = [];
            // 追加先テーブルのカラム名に合わせて配列を整理
            foreach($line as $key => $value){
                // 英語カラムを定義している配列から取得
                $en_column = ColumnChangeHelper::column_en_change($key, StatutoryLeaveUpdateEnum::EN_CHANGE_LIST);
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
            $error_file_name = $ImportErrorCreateService->createImportError('従業員取込エラー', $validation_error);
            throw new ImportException("データが正しくないため、取り込みできませんでした。", ImportEnum::IMPORT_TYPE_UPDATE, $error_file_name, $import_original_file_name);
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
            case '義務の期限':
                if($value === '' || $value === null){
                    $adjustment_value = null;
                    break;
                }
                try {
                    $date = null;
                    if ($value instanceof \DateTimeInterface) {
                        $date = CarbonImmutable::instance($value);
                    } else {
                        foreach (['Y/m/d', 'Y/n/j'] as $format) {
                            try {
                                $date = CarbonImmutable::createFromFormat($format, (string) $value);
                                break; // 成功したら抜ける
                            } catch (\Exception $e) {
                                // 次のフォーマットへ
                            }
                        }
                    }
                    $adjustment_value = $date
                        ? $date->format('Y/m/d')
                        : $value; // 変換不可 → バリデーションへ
                } catch (\Exception $e) {
                    $adjustment_value = $value;
                }
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
                case 'user_name':
                    $rules += ['*.'.$column => 'required|string|max:20'];
                    break;
                case 'statutory_leave_days':
                case 'statutory_leave_remaining_days':
                    $rules += [
                        '*.' . $column => [
                            'required',
                            'numeric',
                            'min:0',
                            'max:5',
                            'regex:/^\d+(\.0|\.5)?$/'
                        ],
                    ];
                    break;
                case 'statutory_leave_expiration_date':
                    $rules += ['*.'.$column => 'required|date_format:Y/m/d'];
                    break;
                default:
                    break;
            }
        }
        // バリデーションエラーメッセージを定義
        $messages = [
            'required'                      => ':attributeは必須です。',
            'employee_no.max'               => ':attribute（:input）は:max文字以内で入力して下さい。',
            'user_name.max'                 => ':attribute（:input）は:max文字以内で入力して下さい。',
            'max'                           => ':attribute（:input）は:max以下で入力して下さい。',
            'min'                           => ':attribute（:input）は:min以上で入力して下さい。',
            'exists'                        => ':attribute（:input）はシステムに存在しません。',
            'numeric'                       => ':attribute（:input）は数値で入力して下さい。',
            'regex'                         => ':attribute（:input）は0.5刻みで入力して下さい。',
            'date_format'                   => ':attribute（:input）はyyyy/mm/dd形式で入力して下さい。',
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            '*.employee_no'                                     => '社員CD',
            '*.user_name'                                       => '社員名',
            '*.statutory_leave_expiration_date'                 => '義務の期限',
            '*.statutory_leave_days'                            => '義務の日数',
            '*.statutory_leave_remaining_days'                  => '義務の残日数',
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
        StatutoryLeaveImport::select()->lockForUpdate()->get();
        // 追加先のテーブルをクリア
        StatutoryLeaveImport::query()->delete();
        // 追加用の配列に入っている情報をテーブルに追加
        StatutoryLeaveImport::insert($create_data);
    }

    // 義務情報を更新
    public function updateStatutoryLeave()
    {
        // 更新対象でステータスが無効の従業員情報を格納する配列を初期化
        $not_update_employees = [];
        // 更新する情報の分だけループ処理
        foreach(StatutoryLeaveImport::all() as $statutory_leave_import){
            // 従業員を取得
            $employee = User::where('employee_no', $statutory_leave_import->employee_no)->lockForUpdate()->first();
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
            // 義務情報を更新
            $employee->statutory_leave->update([
                'statutory_leave_expiration_date'   => $statutory_leave_import->statutory_leave_expiration_date,
                'statutory_leave_days'              => $statutory_leave_import->statutory_leave_days,
                'statutory_leave_remaining_days'    => $statutory_leave_import->statutory_leave_remaining_days,
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