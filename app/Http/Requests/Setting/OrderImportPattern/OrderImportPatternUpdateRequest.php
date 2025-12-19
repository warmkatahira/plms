<?php

namespace App\Http\Requests\Setting\OrderImportPattern;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;
// 列挙
use App\Enums\OrderImportPatternEnum;
// サービス
use App\Services\Setting\OrderImportPattern\OrderImportPatternValidationService;
// その他
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class OrderImportPatternUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // インスタンス化
        $OrderImportPatternValidationService = new OrderImportPatternValidationService;
        // カラムの設定に関するパラメータを取得
        $column_parameters = array_intersect_key($this->all(), OrderImportPatternEnum::SYSTEM_COLUMN_MAPPING);
        // 値がnullと空文字ものを除外する
        $column_parameters = array_filter($column_parameters, function ($value) {
            return $value !== null && $value !== '';
        });
        // 固定値の指定がされているパラメータを取得
        $fixed_parameters = array_filter($this->all(), function ($value, $key) {
            return Str::contains($key, '_fixed');
        }, ARRAY_FILTER_USE_BOTH);
        // 必須カラムが全て揃っているか確認
        $OrderImportPatternValidationService->checkRequiredSystemColumn($column_parameters);
        // カラム取得方法に対応する値が入力されているか確認
        $OrderImportPatternValidationService->checkOrderColumnValue($this->all()['column_get_method'], $column_parameters, $fixed_parameters);
        // システムカラム名の確認
        $OrderImportPatternValidationService->checkSystemColumnName($column_parameters);
        return [
            'order_import_pattern_id'   => 'required|exists:order_import_patterns,order_import_pattern_id',
            'order_category_id'         => 'required|exists:order_categories,order_category_id',
            'pattern_name'              => 'required|string|max:50|unique:order_import_patterns,pattern_name,'.$this->order_import_pattern_id.',order_import_pattern_id',
            'pattern_description'       => 'nullable|string|max:50',
            'column_get_method'         => [
                                            'required',
                                            'string',
                                            'max:10',
                                            Rule::in(array_keys(OrderImportPatternEnum::COLUMN_GET_METHOD)),
            ],
        ];
    }

    public function messages()
    {
        return parent::messages();
    }

    public function attributes()
    {
        return parent::attributes();
    }
}