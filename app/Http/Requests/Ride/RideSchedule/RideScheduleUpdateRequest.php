<?php

namespace App\Http\Requests\Ride\RideSchedule;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class RideScheduleUpdateRequest extends BaseRequest
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
            'ride_id'               => 'required|exists:rides,ride_id',
            'route_type_id'         => 'required|exists:route_types,route_type_id',
            'schedule_date'         => 'required|date',
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