<?php

namespace App\Services\Setting\OrderImportPattern;

// 列挙
use App\Enums\OrderImportPatternEnum;
// その他
use Illuminate\Validation\ValidationException;

class OrderImportPatternValidationService
{
    // 必須カラムが全て揃っているか確認
    public function checkRequiredSystemColumn($column_parameters)
    {
        // $column_parameterに必須カラムがすべて存在するか確認
        $missing = array_diff(OrderImportPatternEnum::REQUIRED_SYSTEM_COLUMN, array_keys($column_parameters));
        // 必須カラムが全て存在していない場合
        if(!empty($missing)){
            // エラーを返す
            throw ValidationException::withMessages([
                '必須のカラムで入力が不足しています。'
            ]);
        }
    }

    // カラム取得方法に対応する値が入力されているか確認
    public function checkOrderColumnValue($column_get_method, $column_parameters, $fixed_parameters)
    {
        // 名称の場合
        if($column_get_method === OrderImportPatternEnum::COLUMN_GET_METHOD_EN_NAME){
            // パラメータの分だけループ処理
            foreach($column_parameters as $column_name => $column_value){
                // 255文字以内の文字列ではない場合
                if(!is_string($column_value) || mb_strlen($column_value) > 255){
                    // エラーを返す
                    throw ValidationException::withMessages([
                        '255文字以内で入力して下さい。'
                    ]);
                }
            }
        }
        // 位置の場合
        if($column_get_method === OrderImportPatternEnum::COLUMN_GET_METHOD_EN_INDEX){
            // パラメータの分だけループ処理
            foreach($column_parameters as $column_name => $column_value){
                // _fixed をつけたキーを生成
                $fixed_key = $column_name . '_fixed';
                // 固定値が存在する場合
                if(isset($fixed_parameters[$fixed_key])){
                    // 255文字以内の文字列ではない場合
                    if(!is_string($column_value) || mb_strlen($column_value) > 255){
                        // エラーを返す
                        throw ValidationException::withMessages([
                            '255文字以内で入力して下さい。'
                        ]);
                    }
                }
                // 固定値が存在しない場合
                if(!isset($fixed_parameters[$fixed_key])){
                    // && または || でスプリット
                    $column_values = preg_split('/(&&|\|\|)/', $column_value);
                    // 値の分だけループ処理
                    foreach($column_values as $value){
                        // 1以上の整数ではない場合
                        if(!ctype_digit((string)$value) || (int)$value < 1){
                            // エラーを返す
                            throw ValidationException::withMessages([
                                '1以上の整数で入力して下さい。'
                            ]);
                        }
                    }
                }
            }
        }
    }

    // システムカラム名の確認
    public function checkSystemColumnName($column_parameters)
    {
        // パラメータの分だけループ処理
        foreach($column_parameters as $column_name => $column_value){
            // 100文字以内の文字列ではない場合
            if(!is_string($column_value) || mb_strlen($column_value) > 100){
                // エラーを返す
                throw ValidationException::withMessages([
                    'システムカラム名が長すぎます。'
                ]);
            }
            // 定義されているシステムカラム名ではない場合
            if(!array_key_exists($column_name, OrderImportPatternEnum::SYSTEM_COLUMN_MAPPING)){
                // エラーを返す
                throw ValidationException::withMessages([
                    'システムカラム名が異常です。'
                ]);
            }
        }
    }
}