<?php

namespace App\Http\Requests\Admin\Route;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class RouteCreateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // 送信されていない = 無効なので、0をパラメータにマージする
        $this->merge([
            'is_active' => $this->has('is_active') ? $this->input('is_active') : 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'route_type_id'         => 'required|exists:route_types,route_type_id',
            'vehicle_category_id'   => 'required|exists:vehicle_categories,vehicle_category_id',
            'route_name'            => 'required|string|max:20|unique:routes,route_name',
            'is_active'             => 'required|boolean',
            'sort_order'            => 'required|integer|min:1|max:200',
        ];
    }

    public function messages()
    {
        return parent::messages();
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'is_active'      => '利用可否',
        ]);
    }
}