<?php

namespace App\Services\Order\OrderImport;

// モデル
use App\Models\Prefecture;
// サービス
use App\Services\Common\ImportErrorCreateService;
use App\Services\Order\OrderImport\OrderImportValidationService;
// 列挙
use App\Enums\OrderImportPatternEnum;
use App\Enums\OrderStatusEnum;
// 例外
use App\Exceptions\OrderImportException;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;

class OrderImportPatternService
{
    // 選択したデータをストレージにインポート
    public function importData($select_file, $nowDate)
    {
        // 選択したデータのファイル名を取得
        $original_file_name = $select_file->getClientOriginalName();
        // 選択したデータの拡張子を取得（例: csv, xlsx）
        $extension = $select_file->getClientOriginalExtension();
        // ストレージに保存する際のファイル名を設定
        $save_file_name = 'order_import_data_'.$nowDate->format('Y-m-d H-i-s').'.'.$extension;
        // ファイルを保存して保存先のパスを取得
        $save_file_path = Storage::disk('public')->putFileAs('import/order_import', $select_file, $save_file_name);
        // 保存先のパスがNullの場合
        if(is_null($save_file_path)){
            throw new OrderImportException('受注データが認識できませんでした。', null, null, null, null);
        }
        // save_file_pathをフルパスに調整する
        return with([
            'original_file_name'    => $original_file_name,
            'save_file_path'        => Storage::disk('public')->path($save_file_path),
        ]);
    }

    // インポートしたデータのヘッダーを確認
    public function checkHeader($save_file_path, $nowDate, $import_info, $order_import_pattern)
    {
        // インスタンス化
        $ImportErrorCreateService = new ImportErrorCreateService;
        // 全データを取得
        $all_line = (new FastExcel)->import($save_file_path);
        // インポートしたデータのヘッダーを取得
        $import_data_header = array_keys(mb_convert_encoding($all_line[0], 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win'));
        // インデックスを+1する（カラム位置用に0始まりを1始まりに変えている）
        $import_data_header = array_combine(
            range(1, count($import_data_header)), 
            $import_data_header
        );
        // カラム名 or 位置を格納する配列を初期化
        $order_columns = [];
        // カラム取得方法に合わせて、値を取得するカラムを設定
        $column_get_method_column = 'order_column_'.$order_import_pattern->column_get_method;
        // 受注取込パターン詳細の分だけループ処理
        foreach($order_import_pattern->order_import_pattern_details as $order_import_pattern_detail){
            // 受注を参照する設定を変数に格納
            $pattern = $order_import_pattern_detail->$column_get_method_column;
            // nullの場合
            if(is_null($pattern)){
                // 次のループへ
                continue;
            }
            // && または || でスプリット
            $patterns = preg_split('/(&&|\|\|)/', $pattern);
            // カラムの分だけループ処理
            foreach($patterns as $column){
                // カラム名を格納
                $order_columns[] = trim($column);
            }
        }
        // ヘッダーの分だけループ処理
        foreach($order_columns as $index => $order_column){
            // ヘッダーが存在するか確認
            $result = $this->checkValueExists($import_data_header, $order_column, $order_import_pattern->column_get_method);
            // nullではない場合
            if(!is_null($result)){
                $result = array_map(fn($msg) => [$msg], array($result));
                // エラーファイルを作成
                $error_file_name = $ImportErrorCreateService->createImportError('受注取込エラー', $result, $nowDate, ['内容']);
                throw new OrderImportException('ファイルが正しくないため、取り込みできませんでした。', $import_info, null, $error_file_name);
            }
        }
    }

    // 配列の値が存在しているか確認
    public function checkValueExists($array, $value, $column_get_method){
        // カラム取得方法が「名称」の場合
        if($column_get_method === OrderImportPatternEnum::COLUMN_GET_METHOD_EN_NAME){
            // 存在したら「true」、存在しなかったら「false」
            $result = in_array($value, $array);
            // 存在しなかったら、エラーを返す
            return !$result ? 'カラムに「'.$value.'」がありません。' : null;
        }
        // カラム取得方法が「位置」の場合
        if($column_get_method === OrderImportPatternEnum::COLUMN_GET_METHOD_EN_INDEX){
            // 存在したら「true」、存在しなかったら「false」
            $result = array_key_exists($value, $array);
            // 存在しなかったら、エラーを返す
            return !$result ? 'カラムに「'.$value.'」列目がありません。' : null;
        }
    }

    // 追加する受注データを配列に格納（同時にバリデーションも実施）
    public function setOrderArray($save_file_path, $nowDate, $order_import_pattern)
    {
        // インスタンス化
        $OrderImportValidationService = new OrderImportValidationService;
        // データの情報を取得
        $all_line = (new FastExcel)->import($save_file_path);
        // テーブルへ追加する情報を格納する配列を初期化
        $order_create_data = [];
        // バリデーションエラーを格納する配列を初期化
        $validation_error   = [];
        // 取得したレコードの分だけループ
        foreach($all_line as $key => $line){
            // 受注情報を格納する配列を初期化
            $param = [];
            // UTF-8形式に変換した1行分のデータを取得
            $line = mb_convert_encoding($line, 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win');
            // カラム取得方法が「位置」の場合
            if($order_import_pattern->column_get_method === OrderImportPatternEnum::COLUMN_GET_METHOD_EN_INDEX){
                // キーを列番号に変換
                $line = array_combine(
                    range(1, count($line)),
                    array_values($line)
                );
            }
            // 受注取込パターン詳細の分だけループ処理
            foreach($order_import_pattern->order_import_pattern_details as $order_import_pattern_detail){
                // 受注の値を格納する変数を初期化
                $value = null;
                // 固定値の判別をする変数を初期化
                $is_fixed = false;
                // カラム取得方法が「名称」の場合
                if($order_import_pattern->column_get_method === OrderImportPatternEnum::COLUMN_GET_METHOD_EN_NAME){
                    // 受注カラム名を変数に格納
                    $pattern_value = $order_import_pattern_detail->order_column_name;
                }
                // カラム取得方法が「位置」の場合
                if($order_import_pattern->column_get_method === OrderImportPatternEnum::COLUMN_GET_METHOD_EN_INDEX){
                    // 受注カラム位置を変数に格納
                    $pattern_value = $order_import_pattern_detail->order_column_index;
                }
                // 固定値がある場合
                if($order_import_pattern_detail->fixed_value !== null){
                    // 固定値を変数に格納
                    $pattern_value = $order_import_pattern_detail->fixed_value;
                    // 変数をtrueに変更
                    $is_fixed = true;
                }
                // 固定値ではない場合
                if(!$is_fixed){
                    // 「&&」または「||」でスプリット
                    $pattern_values = preg_split('/(&&|\|\|)/', $pattern_value);
                    // 「&&」を含む場合
                    if(strpos($pattern_value, '&&') !== false){
                        // カラムの分だけループ処理
                        foreach($pattern_values as $pattern){
                            // 値を格納
                            $value .= $line[$pattern];
                        }
                    }
                    // 「||」を含む場合
                    if(strpos($pattern_value, '||') !== false){
                        // カラムの分だけループ処理
                        foreach($pattern_values as $pattern){
                            // 値がある場合
                            if(isset($line[$pattern]) && !is_null($line[$pattern]) && $line[$pattern] !== ''){
                                // 値を格納
                                $value = $line[$pattern];
                                // ループを抜ける
                                break;
                            }
                        }
                    }
                    // 「&&」も「||」も含まない場合
                    if(!preg_match('/(&&|\|\|)/', $pattern_value)){
                        // 値を格納
                        $value = $line[$pattern_values[0]];
                    }
                }
                // 固定値の場合
                if($is_fixed){
                    // 値を格納
                    $value = $pattern_value;
                }
                // 配列に値を格納
                $param[$order_import_pattern_detail->system_column_name] = $value;
            }
            // 共通の情報を配列に追加
            // 取込日時
            $param['order_import_date'] = $nowDate->toDateString();
            $param['order_import_time'] = $nowDate->toTimeString();
            $param['order_status_id'] = OrderStatusEnum::KAKUNIN_MACHI;
            // 配送先住所から都道府県を取得
            $param['ship_province_name'] = Prefecture::extractPrefecture($param['ship_address']);
            // 受注区分
            $param['order_category_id'] = $order_import_pattern->order_category_id;
            // 共通の処理
            // 郵便番号を変換して変数に格納
            $param['ship_postal_code'] = substr(str_replace("-", "", $param['ship_postal_code']), 0, 3).'-'.substr(str_replace("-", "", $param['ship_postal_code']), 3);
            // 注文日をフォーマット化
            $param['order_date'] = CarbonImmutable::parse($param['order_date'])->toDateString();
            // 値が空であれば、nullを格納
            $param = array_map(function ($value){
                return $value === "" ? null : $value;
            }, $param);
            // インポートデータのバリデーション処理
            $message = $OrderImportValidationService->validation($key + 2, $param);
            // エラーメッセージがある場合
            if(!is_null($message)){
                // バリデーションエラーを配列に格納
                $validation_error[] = array_combine(['エラー行数', 'エラー内容'], $message);
            }
            // 追加用の配列に整理した情報を格納
            $order_create_data[] = $param;
        }
        // バリデーションエラーにnull以外があれば、エラー情報を出力
        if(count(array_filter($validation_error)) != 0){
            // インスタンス化
            $ImportErrorCreateService   = new ImportErrorCreateService;
            // エラー情報のファイルを作成
            $error_file_name = $ImportErrorCreateService->createImportError('受注取込エラー', $validation_error, $nowDate, ['行数', '内容']);
            throw new OrderImportException("データが正しくないため、取り込みできませんでした。", null, null, $error_file_name);
        }
        return $order_create_data;
    }
}