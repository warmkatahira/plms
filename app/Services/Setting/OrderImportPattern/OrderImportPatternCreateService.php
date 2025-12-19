<?php

namespace App\Services\Setting\OrderImportPattern;

// モデル
use App\Models\OrderImportPattern;
use App\Models\OrderImportPatternDetail;
// 列挙
use App\Enums\OrderImportPatternEnum;
// その他
use Illuminate\Support\Str;

class OrderImportPatternCreateService
{
    // 受注取込パターンを追加
    public function createOrderImportPattern($request)
    {
        // 受注取込パターンを追加
        $order_import_pattern = OrderImportPattern::create([
            'pattern_name'          => $request->pattern_name,
            'pattern_description'   => $request->pattern_description,
            'column_get_method'     => $request->column_get_method,
            'order_category_id'     => $request->order_category_id,
        ]);
        // カラム取得位置によって追加先のカラム名を可変
        $column_get_method_column = 'order_column_'.$request->column_get_method;
        // カラムの設定に関するパラメータを取得
        $column_parameters = array_intersect_key($request->all(), OrderImportPatternEnum::SYSTEM_COLUMN_MAPPING);
        // 値がnullと空文字ものを除外する
        $column_parameters = array_filter($column_parameters, function ($value) {
            return $value !== null && $value !== '';
        });
        // 固定値の指定がされているパラメータを取得
        $fixed_parameters = array_filter($request->all(), function ($value, $key) {
            return Str::contains($key, '_fixed');
        }, ARRAY_FILTER_USE_BOTH);
        // 受注取込パターン詳細に追加する情報を格納する配列を初期化
        $data = [];
        // パラメータの分だけループ処理
        foreach($column_parameters as $column_name => $column_value){
            // 各カラムに入れる値を格納する変数を初期化
            $column_get_method_column_value = $column_value;
            $fixed_value = null;
            // _fixed をつけたキーを生成
            $fixed_key = $column_name . '_fixed';
            // 固定値が存在する場合
            if(isset($fixed_parameters[$fixed_key])){
                // 固定値用のカラムを変数に格納
                $fixed_value = $column_value;
                // カラム取得位置に対応する値はnullに変更
                $column_get_method_column_value = null;
            }
            // 配列に情報を追加
            $data[] = [
                'order_import_pattern_id'   => $order_import_pattern->order_import_pattern_id,
                'system_column_name'        => $column_name,
                $column_get_method_column   => $column_get_method_column_value,
                'fixed_value'               => $fixed_value,
            ];
        }
        // 受注取込パターン詳細を追加
        OrderImportPatternDetail::upsert($data, 'order_import_pattern_detail_id');
    }
}