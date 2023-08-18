<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'firts_name' => 'required',
            'last_name' => 'required',
            'mother_last_name' => 'required',
            'type_doc' => 'required',
            'num_doc' => 'required',
            'email' => 'required|email',
            'send_email_sponsor',
            'password' => 'required|min:8',
            //'password_confirmation' => 'required',
        ];
    }
}
