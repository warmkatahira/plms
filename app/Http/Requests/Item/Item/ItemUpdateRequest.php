<?php

namespace App\Http\Requests\Item\Item;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class ItemUpdateRequest extends BaseRequest
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
            'item_id'                           => 'required|exists:items,item_id',
            'item_jan_code'                     => 'required_if:is_shipping_inspection_required,1|string|max:13',
            'item_name'                         => 'required|string|max:255',
            'item_category_1'                   => 'nullable|string|max:100',
            'item_category_2'                   => 'nullable|string|max:100',
            'is_stock_managed'                  => 'required|boolean',
            'is_shipping_inspection_required'   => 'required|boolean',
            'image_file'                        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sort_order'                        => 'nullable|integer|min:1',
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