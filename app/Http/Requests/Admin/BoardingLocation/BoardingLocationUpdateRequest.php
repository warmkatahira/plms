<?php

namespace App\Http\Requests\Admin\BoardingLocation;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class BoardingLocationUpdateRequest extends BaseRequest
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
            'boarding_location_id'  => 'required|exists:boarding_locations,boarding_location_id',
            'location_name'         => 'required|string|max:10|unique:boarding_locations,location_name',
            'location_memo'         => 'nullable|string|max:50',
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