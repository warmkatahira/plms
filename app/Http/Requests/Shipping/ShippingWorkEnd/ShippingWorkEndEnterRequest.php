<?php

namespace App\Http\Requests\Shipping\ShippingWorkEnd;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class ShippingWorkEndEnterRequest extends BaseRequest
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
            'shipping_group_id' => 'required|exists:shipping_groups,shipping_group_id',
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