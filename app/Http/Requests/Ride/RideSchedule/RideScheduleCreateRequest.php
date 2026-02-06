<?php

namespace App\Http\Requests\Ride\RideSchedule;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class RideScheduleCreateRequest extends BaseRequest
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
        // 送迎日を配列に変換
        if($this->filled('schedule_date')){
            $this->merge([
                'schedule_dates' => array_filter(
                    array_map('trim', explode(',', $this->schedule_date))
                ),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'schedule_dates'        => 'required|array',
            'schedule_dates.*'      => 'required|date',
            'driver_user_no'        => 'nullable|exists:users,user_no',
            'use_vehicle_id'        => 'nullable|exists:vehicles,vehicle_id',
            'ride_memo'             => 'nullable|string|max:50',
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
            'is_active'      => '運行状況',
        ]);
    }
}