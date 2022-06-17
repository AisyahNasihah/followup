<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['string'],
            'phone_number' => ['regex:/(01)[0-9]{8}/', 'unique:users'],
            'email' => ['email', 'unique:users'],
            'old_password' => ['required_unless:new_password,null', 'string', 'current_password:sanctum'],
            'new_password' => ['required_unless:old_password,null', 'string', 'min:8', 'confirmed'],
        ];
    }
}
