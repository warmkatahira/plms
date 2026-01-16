<?php

namespace App\Services\Admin\Employee;

// モデル
use App\Models\User;
use App\Models\Base;
use App\Models\PaidLeave;
use App\Models\StatutoryLeave;
use App\Models\EmployeeImport;
// サービス
use App\Services\Common\ImportErrorCreateService;
// ヘルパー
use App\Helpers\ColumnChangeHelper;
// 列挙
use App\Enums\RoleEnum;
use App\Enums\SystemEnum;
use App\Enums\EmployeeCreateEnum;
use App\Enums\WorkingHoursEnum;
use App\Enums\ImportEnum;
// 例外
use App\Exceptions\ImportException;
// その他
use App\Mail\UserCreateNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\CarbonImmutable;
use Illuminate\Validation\Rule;

class EmployeeCreateService
{
    // 従業員を追加
    public function createEmployee($request)
    {
        // 従業員を追加
        $user = User::create([
            'status'                                        => $request->status,
            'base_id'                                       => $request->base_id,
            'employee_no'                                   => $request->employee_no,
            'user_name'                                     => $request->user_name,
            'user_id'                                       => $request->user_id,
            'password'                                      => Hash::make($request->password),
            'is_auto_update_statutory_leave_remaining_days' => $request->is_auto_update_statutory_leave_remaining_days,
            'role_id'                                       => RoleEnum::USER,
        ]);
        // 有給関連テーブルへレコード追加
        $this->createPaidLeave($user, $request);
        // アカウント発行通知メールを送信
        //$this->sendMail($user, $password);
    }

    // 有給関連テーブルへレコード追加
    public function createPaidLeave($user, $request)
    {
        // 有給管理テーブルへ追加
        PaidLeave::create([
            'user_no'                   => $user->user_no,
            'paid_leave_granted_days'   => $request->paid_leave_granted_days,
            'paid_leave_remaining_days' => $request->paid_leave_remaining_days,
            'paid_leave_used_days'      => $request->paid_leave_used_days,
            'daily_working_hours'       => $request->daily_working_hours,
            'half_day_working_hours'    => $request->half_day_working_hours,
        ]);
        // 有給義務管理テーブルへ追加
        StatutoryLeave::create([
            'user_no'                           => $user->user_no,
            'statutory_leave_expiration_date'   => $request->statutory_leave_expiration_date,
            'statutory_leave_days'              => $request->statutory_leave_days,
            'statutory_leave_remaining_days'    => $request->statutory_leave_remaining_days,
        ]);
    }

    // アカウント発行通知メールを送信
    public function sendMail($user, $password)
    {
        // +-+-+-+-+-+-+-+-+-+-   アカウント発行通知メール   +-+-+-+-+-+-+-+-+-+-
        // インスタンス化
        $mail = new UserCreateNotificationMail($user, $password);
        // Toを設定
        $mail->to(Auth::user()->email);
        // 件名を設定
        $mail->subject('【'.SystemEnum::getSystemTitle().'】従業員追加通知');
        // メールを送信
        Mail::send($mail);
        // +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
    }

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
                $en_column = ColumnChangeHelper::column_en_change($key, EmployeeCreateEnum::EN_CHANGE_LIST);
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
            throw new ImportException("データが正しくないため、取り込みできませんでした。", ImportEnum::IMPORT_PROCESS_EMPLOYEE, ImportEnum::IMPORT_TYPE_CREATE, $error_file_name, $import_original_file_name);
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
            case 'ステータス':
            case '義務残日数自動更新':
                // 無効を「0」、有効を「1」に変換
                $adjustment_value = $value === '無効' ? 0 : ($value === '有効' ? 1 : $value);
                break;
            case '営業所':
                // 省略営業所名から営業所IDに変換
                $adjustment_value = Base::getBaseIdByShortBaseName($value);
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
                case 'status':
                case 'is_auto_update_statutory_leave_remaining_days':
                    $rules += ['*.'.$column => 'nullable|boolean'];
                    break;
                case 'short_base_name':
                    $rules += ['*.'.$column => 'required|exists:bases,base_id'];
                    break;
                case 'employee_no':
                    $rules += ['*.'.$column => 'required|string|max:4|unique:users,employee_no'];
                    break;
                case 'user_name':
                    $rules += ['*.'.$column => 'required|string|max:20'];
                    break;
                case 'user_id':
                    $rules += ['*.'.$column => 'required|string|max:20|unique:users,user_id'];
                    break;
                case 'password':
                    $rules += ['*.'.$column => 'required|string|max:255'];
                    break;
                case 'paid_leave_granted_days':
                case 'paid_leave_remaining_days':
                case 'paid_leave_used_days':
                    $rules += [
                        '*.' . $column => [
                            'nullable',
                            'numeric',
                            'min:0',
                            'max:20',
                            'regex:/^\d+(\.0|\.5)?$/'
                        ],
                    ];
                    break;
                case 'statutory_leave_days':
                case 'statutory_leave_remaining_days':
                    $rules += [
                        '*.' . $column => [
                            'nullable',
                            'numeric',
                            'min:0',
                            'max:5',
                            'regex:/^\d+(\.0|\.5)?$/'
                        ],
                    ];
                    break;
                case 'daily_working_hours':
                    $rules += [
                        '*.' . $column => [
                            'nullable',
                            Rule::in(array_keys(WorkingHoursEnum::DAILY_WORKING_HOURS)),
                        ],
                    ];
                    break;
                case 'half_day_working_hours':
                    $rules += [
                        '*.' . $column => [
                            'nullable',
                            Rule::in(array_keys(WorkingHoursEnum::HALF_DAY_WORKING_HOURS)),
                        ],
                    ];
                    break;
                case 'statutory_leave_expiration_date':
                    $rules += ['*.'.$column => 'nullable|date_format:Y/m/d'];
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
            'user_id.max'                   => ':attribute（:input）は:max文字以内で入力して下さい。',
            'password.max'                  => ':attribute（:input）は:max文字以内で入力して下さい。',
            'max'                           => ':attribute（:input）は:max以下で入力して下さい。',
            'min'                           => ':attribute（:input）は:min以上で入力して下さい。',
            'boolean'                       => ':attribute（:input）が正しくありません。',
            'exists'                        => ':attribute（:input）はシステムに存在しません。',
            'numeric'                       => ':attribute（:input）は数値で入力して下さい。',
            'unique'                        => ':attribute（:input）は既に使用されています。',
            'regex'                         => ':attribute（:input）は0.5刻みで入力して下さい。',
            'date_format'                   => ':attribute（:input）はyyyy/mm/dd形式で入力して下さい。',
            'in'                            => ':attribute（:input）が正しくありません。',
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            '*.status'                                          => 'ステータス',
            '*.short_base_name'                                 => '営業所',
            '*.employee_no'                                     => '社員CD',
            '*.user_name'                                       => '社員名',
            '*.user_id'                                         => 'ID',
            '*.password'                                        => 'パスワード',
            '*.paid_leave_granted_days'                         => '保有日数',
            '*.paid_leave_remaining_days'                       => '残日数',
            '*.paid_leave_used_days'                            => '取得日数',
            '*.daily_working_hours'                             => '1日あたりの時間数',
            '*.half_day_working_hours'                          => '半日あたりの時間数',
            '*.is_auto_update_statutory_leave_remaining_days'   => '義務残日数自動更新',
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
        EmployeeImport::select()->lockForUpdate()->get();
        // 追加先のテーブルをクリア
        EmployeeImport::query()->delete();
        // 追加用の配列に入っている情報をテーブルに追加
        EmployeeImport::insert($create_data);
    }

    // 従業員を追加
    public function createEmployeeByImport()
    {
        // 追加する情報の分だけループ処理
        foreach(EmployeeImport::all() as $employee_import){
            // 従業員を追加
            $user = User::create([
                'status'        => $employee_import->status,
                'base_id'       => $employee_import->short_base_name,
                'employee_no'   => $employee_import->employee_no,
                'user_name'     => $employee_import->user_name,
                'user_id'       => $employee_import->user_id,
                'password'      => Hash::make($employee_import->password),
                'role_id'       => RoleEnum::USER,
            ]);
            // 有給関連テーブルへレコード追加
            $this->createPaidLeave($user, $employee_import);
            // アカウント発行通知メールを送信
            //$this->sendMail($user, $password);
            // テーブルをクリア
            EmployeeImport::query()->delete();
        }
    }
}