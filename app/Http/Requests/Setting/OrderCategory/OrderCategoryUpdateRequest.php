<?php

namespace App\Http\Requests\Setting\OrderCategory;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class OrderCategoryUpdateRequest extends BaseRequest
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
            'order_category_id'                 => 'required|exists:order_categories,order_category_id',
            'order_category_name'               => 'required|string|max:100',
            'mall_id'                           => 'required|exists:malls,mall_id',
            'shipper_id'                        => 'required|exists:shippers,shipper_id',
            'nifuda_product_name_1'             => 'required|string|max:16',
            'nifuda_product_name_2'             => 'nullable|string|max:16',
            'nifuda_product_name_3'             => 'nullable|string|max:16',
            'nifuda_product_name_4'             => 'nullable|string|max:16',
            'nifuda_product_name_5'             => 'nullable|string|max:16',
            'app_id'                            => 'nullable|string|max:10|required_with:access_token,api_key',
            'access_token'                      => 'nullable|string|max:255|required_with:app_id,api_key',
            'api_key'                           => 'nullable|string|max:255|required_with:app_id,access_token',
            'sort_order'                        => 'required|integer|min:1',
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