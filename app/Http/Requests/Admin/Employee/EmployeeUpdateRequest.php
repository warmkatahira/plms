<?php

namespace App\Http\Requests\Admin\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;
// その他
use Illuminate\Validation\Rule;
// 列挙
use App\Enums\WorkingHoursEnum;

class EmployeeUpdateRequest extends BaseRequest
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
            'user_no'                                       => 'required|exists:users,user_no',
            'status'                                        => 'required|boolean',
            'base_id'                                       => 'required|exists:bases,base_id',
            'employee_no'                                   => 'required|string|max:4|unique:users,employee_no,'.$this->user_no.',user_no',
            'user_name'                                     => 'required|string|max:20',
            'paid_leave_granted_days'                       => ['required','numeric','min:0','max:20','regex:/^\d+(\.0|\.5)?$/'],
            'paid_leave_remaining_days'                     => ['required','numeric','min:0','max:20','regex:/^\d+(\.0|\.5)?$/'],
            'paid_leave_used_days'                          => ['required','numeric','min:0','max:20','regex:/^\d+(\.0|\.5)?$/'],
            'statutory_leave_days'                          => ['required','numeric','min:0','max:20','regex:/^\d+(\.0|\.5)?$/'],
            'statutory_leave_remaining_days'                => ['required','numeric','min:0','max:20','regex:/^\d+(\.0|\.5)?$/'],
            'daily_working_hours'                           => ['required',Rule::in(array_keys(WorkingHoursEnum::DAILY_WORKING_HOURS)),],
            'half_day_working_hours'                        => ['required',Rule::in(array_keys(WorkingHoursEnum::HALF_DAY_WORKING_HOURS)),],
            'statutory_leave_expiration_date'               => 'required|date_format:Y-m-d',
            'is_auto_update_statutory_leave_remaining_days' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'paid_leave_granted_days.regex'         => ":attributeは0.5刻みで入力して下さい。",
            'paid_leave_remaining_days.regex'       => ":attributeは0.5刻みで入力して下さい。",
            'paid_leave_used_days.regex'            => ":attributeは0.5刻みで入力して下さい。",
            'statutory_leave_days.regex'            => ":attributeは0.5刻みで入力して下さい。",
            'statutory_leave_remaining_days.regex'  => ":attributeは0.5刻みで入力して下さい。",
            'paid_leave_granted_days.min'           => ":attributeは:min以上で入力して下さい。",
            'paid_leave_remaining_days.min'         => ":attributeは:min以上で入力して下さい。",
            'paid_leave_used_days.min'              => ":attributeは:min以上で入力して下さい。",
            'statutory_leave_days.min'              => ":attributeは:min以上で入力して下さい。",
            'statutory_leave_remaining_days.min'    => ":attributeは:min以上で入力して下さい。",
            'paid_leave_granted_days.max'           => ":attributeは:max以下で入力して下さい。",
            'paid_leave_remaining_days.max'         => ":attributeは:max以下で入力して下さい。",
            'paid_leave_used_days.max'              => ":attributeは:max以下で入力して下さい。",
            'statutory_leave_days.max'              => ":attributeは:max以下で入力して下さい。",
            'statutory_leave_remaining_days.max'    => ":attributeは:max以下で入力して下さい。",
        ]);
    }

    public function attributes()
    {
        return parent::attributes();
    }
}