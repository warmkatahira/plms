<?php

namespace App\Http\Requests\Admin\RouteDetail;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class RouteDetailUpdateRequest extends BaseRequest
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
            'route_id'                  => 'required|exists:routes,route_id',
            'boarding_location_id.*'    => 'required|exists:boarding_locations,boarding_location_id',
            'stop_order.*'              => 'required|integer|min:1|max:100|distinct',
            'departure_time.*'          => ['nullable','regex:/^\d{2}:\d{2}(:\d{2})?$/','distinct','required_without:arrival_time.*'],
            'arrival_time.*'            => ['nullable','regex:/^\d{2}:\d{2}(:\d{2})?$/','distinct','required_without:departure_time.*'],
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'stop_order.*.min'              => ":attributeは:min以上で入力して下さい。",
            'stop_order.*.max'              => ":attributeは:max以内で入力して下さい。",
            'regex'                         => ":attributeは時刻を入力して下さい。",
            'required_without'              => "出発時刻か到着時刻のどちらかは入力して下さい。",
        ]);
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'boarding_location_id.*'    => '乗降場所',
            'stop_order.*'              => '停車順番',
            'departure_time.*'          => '出発時刻',
            'arrival_time.*'            => '到着時刻',
        ]);
    }
}