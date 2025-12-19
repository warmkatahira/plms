<?php

namespace App\Services\Order\OrderImport;

// その他
use Illuminate\Support\Facades\Validator;
// 列挙
use App\Enums\OrderImportValidationEnum;

class OrderImportValidationService
{
    // バリデーション処理
    public function validation($order_no, $param)
    {
        // バリデーションルールを定義
        $rules = OrderImportValidationEnum::RULES;
        // バリデーションエラーメッセージを定義
        $messages = OrderImportValidationEnum::MESSAGES;
        // バリデーションエラー項目を定義
        $attributes = OrderImportValidationEnum::ATTRIBUTES;
        // バリデーション実施
        $validator = Validator::make($param, $rules, $messages, $attributes);
        // バリデーションエラーメッセージを格納する変数を初期化
        $message = '';
        // バリデーションエラーの分だけループ
        foreach($validator->errors()->toArray() as $errors){
            // メッセージを格納
            $message = empty($message) ? array_shift($errors) : $message . ' / ' . array_shift($errors);
        }
        return empty($message) ? null : array($order_no, $message);
    }
}