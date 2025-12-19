<?php

namespace App\Http\Requests\Setting\OrderImportPattern;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class OrderImportPatternDeleteRequest extends BaseRequest
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
            'order_import_pattern_id'     => 'required|exists:order_import_patterns,order_import_pattern_id',
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