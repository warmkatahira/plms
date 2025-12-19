<?php

namespace App\Http\Requests\SystemAdmin\Company;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class CompanyCreateRequest extends BaseRequest
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
            'company_id'            => 'required|string|max:10|unique:companies,company_id',
            'company_name'          => 'required|string|max:20|unique:companies,company_name',
            'sort_order'            => 'required|integer|min:1|max:100',
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