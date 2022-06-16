<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'company' => ['required_without:name', 'string'],
            'name' => ['required_without:company', 'string'],
            'phone_number' => ['required_without:email', 'regex:/(01)[0-9]{8}/'],
            'email' => ['required_without:phone_number', 'email'],
            'category' => ['required', 'string'],
        ];
    }
}
