<?php

namespace App\Http\Requests\Admin\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class EmployeeCreateRequest extends BaseRequest
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
            'status' => $this->has('status') ? $this->input('status') : 0,
            'is_auto_update_statutory_leave_remaining_days' => $this->has('is_auto_update_statutory_leave_remaining_days') ? $this->input('is_auto_update_statutory_leave_remaining_days') : 0,
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
            'status'                                        => 'required|boolean',
            'base_id'                                       => 'required|exists:bases,base_id',
            'employee_no'                                   => 'required|string|max:4|unique:users,employee_no',
            'user_name'                                     => 'required|string|max:20',
            'user_id'                                       => 'required|string|max:20|unique:users,user_id',
            'is_auto_update_statutory_leave_remaining_days' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'regex'     => ':attributeは英数字のみで入力して下さい。',
        ]);
    }

    public function attributes()
    {
        return parent::attributes();
    }
}