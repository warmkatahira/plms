<?php

namespace App\Http\Requests\SystemAdmin\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class UserUpdateRequest extends BaseRequest
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
            'user_no'           => 'required|exists:users,user_no',
            'last_name'         => 'required|string|max:20',
            'first_name'        => 'required|string|max:20',
            'email'             => 'required|email|unique:users,email,'.$this->user_no.',user_no',
            'status'            => 'required|boolean',
            'role_id'           => 'required|exists:roles,role_id',
            'company_id'        => 'required|exists:companies,company_id',
        ];
    }

    public function messages()
    {
        return parent::messages();
    }

    public function attributes()
    {
        return parent::attributes();
    }
}