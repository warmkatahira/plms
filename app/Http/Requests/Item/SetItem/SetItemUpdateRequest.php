<?php

namespace App\Http\Requests\Item\SetItem;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class SetItemUpdateRequest extends BaseRequest
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
            'set_item_name'             => 'required|string|max:255',
            'shipping_method_id'        => 'required|exists:shipping_methods,shipping_method_id',
            'image_file'                => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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