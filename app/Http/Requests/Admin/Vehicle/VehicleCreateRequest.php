<?php

namespace App\Http\Requests\Admin\Vehicle;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class VehicleCreateRequest extends BaseRequest
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
            'owner'                 => 'nullable|exists:users,user_no',
            'vehicle_type_id'       => 'required|exists:vehicle_types,vehicle_type_id',
            'vehicle_category_id'   => 'required|exists:vehicle_categories,vehicle_category_id',
            'vehicle_name'          => 'required|string|max:10',
            'vehicle_color'         => 'required|string|max:5',
            'vehicle_number'        => 'required|string|max:4',
            'vehicle_capacity'      => 'required|integer|min:1|max:20',
            'vehicle_memo'          => 'nullable|string|max:50',
            'is_active'             => 'required|boolean',
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