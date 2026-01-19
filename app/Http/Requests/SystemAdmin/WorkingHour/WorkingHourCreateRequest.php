<?php

namespace App\Http\Requests\SystemAdmin\WorkingHour;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;
// 列挙
use App\Enums\WorkingHourEnum;
// その他
use Illuminate\Validation\Rule;

class WorkingHourCreateRequest extends BaseRequest
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
            'working_type'               => [
                                                'required',
                                                Rule::in(array_keys(WorkingHourEnum::WORKING_TYPE_LIST)),
                                            ],
            'working_hour'               => [
                                                'required',
                                                'numeric',
                                                'min:0.25',
                                                'max:10',
                                                'regex:/^\d+(\.(00|25|50|75))?$/',
                                                Rule::unique('working_hours', 'working_hour')
                                                    ->where(fn ($q) =>
                                                        $q->where('working_type', request('working_type'))
                                                    ),
                                            ],
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'working_hour.regex'    => ':attributeは0.25刻みで入力して下さい。',
            'working_hour.max'      => ':attributeは:max以下で入力して下さい。',
            'working_hour.min'      => ':attributeは:min以上で入力して下さい。',
        ]);
    }

    public function attributes()
    {
        return parent::attributes();
    }
}