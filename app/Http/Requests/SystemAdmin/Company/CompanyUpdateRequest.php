<?php

namespace App\Http\Requests\SystemAdmin\Company;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class CompanyUpdateRequest extends BaseRequest
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
            'company_name'          => 'required|string|max:20|unique:companies,company_name,'.$this->company_id.',company_id',
            'sort_order'            => 'required|integer|min:1|max:100',
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'regex'                             => ":attributeが正しくありません。",
        ]);
    }

    public function attributes()
    {
        return parent::attributes();
    }
}