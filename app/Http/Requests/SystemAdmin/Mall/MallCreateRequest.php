<?php

namespace App\Http\Requests\SystemAdmin\Mall;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class MallCreateRequest extends BaseRequest
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
        return [
            'mall_name'             => 'required|string|max:20|unique:malls,mall_name',
            'image_file'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'image_file.max'                    => ":attributeは:max KB以下の画像を選択してください。",
        ]);
    }

    public function attributes()
    {
        return parent::attributes();
    }
}